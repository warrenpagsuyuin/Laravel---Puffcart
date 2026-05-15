import { gsap } from 'gsap';
import * as THREE from 'three';
import { EffectComposer } from 'three/examples/jsm/postprocessing/EffectComposer.js';
import { GLTFLoader } from 'three/examples/jsm/loaders/GLTFLoader.js';
import { RenderPass } from 'three/examples/jsm/postprocessing/RenderPass.js';
import { UnrealBloomPass } from 'three/examples/jsm/postprocessing/UnrealBloomPass.js';

const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

class VapeShowcase {
    constructor(root) {
        this.root = root;
        this.canvas = root.querySelector('[data-showcase-canvas]');
        this.loaderEl = root.querySelector('[data-showcase-loader]');
        this.progressEl = root.querySelector('[data-showcase-progress]');
        this.fallbackEl = root.querySelector('[data-showcase-fallback]');
        this.modelUrl = root.dataset.model || '/models/xros.glb';
        this.pointer = { x: 0, y: 0, targetX: 0, targetY: 0 };
        this.clock = new THREE.Clock();
        this.frameId = null;
        this.disposed = false;

        if (!this.canvas) return;

        this.initScene();
        this.addLighting();
        this.addFloor();
        this.addParticles();
        this.bindEvents();
        this.loadModel();
        this.animate();
    }

    initScene() {
        this.scene = new THREE.Scene();
        this.scene.fog = new THREE.FogExp2(0xf7fbff, 0.028);

        this.camera = new THREE.PerspectiveCamera(36, 1, 0.1, 100);
        this.camera.position.set(0, 0.34, 7.4);

        this.renderer = new THREE.WebGLRenderer({
            canvas: this.canvas,
            alpha: true,
            antialias: true,
            preserveDrawingBuffer: true,
            powerPreference: 'high-performance',
        });
        this.renderer.setPixelRatio(Math.min(window.devicePixelRatio || 1, 1.75));
        this.renderer.outputColorSpace = THREE.SRGBColorSpace;
        this.renderer.toneMapping = THREE.ACESFilmicToneMapping;
        this.renderer.toneMappingExposure = 1.18;
        this.renderer.shadowMap.enabled = true;
        this.renderer.shadowMap.type = THREE.PCFSoftShadowMap;

        this.composer = new EffectComposer(this.renderer);
        this.renderPass = new RenderPass(this.scene, this.camera);
        this.bloomPass = new UnrealBloomPass(new THREE.Vector2(1, 1), 0.38, 0.42, 0.72);
        this.composer.addPass(this.renderPass);
        this.composer.addPass(this.bloomPass);

        this.resize();

        gsap.fromTo(
            this.camera.position,
            { z: 9.8, y: 0.75 },
            { z: 7.4, y: 0.34, duration: 1.8, ease: 'power3.out', delay: 0.18 }
        );
    }

    addLighting() {
        this.scene.add(new THREE.HemisphereLight(0xf7fbff, 0xd9e8ff, 1.35));

        const key = new THREE.DirectionalLight(0xffffff, 4.2);
        key.position.set(-3.7, 5.2, 4.4);
        key.castShadow = true;
        key.shadow.mapSize.set(1024, 1024);
        key.shadow.camera.near = 0.1;
        key.shadow.camera.far = 18;
        this.scene.add(key);

        const rim = new THREE.DirectionalLight(0x73a7ff, 3.2);
        rim.position.set(4.8, 2.2, -2.8);
        this.scene.add(rim);

        this.blueGlow = new THREE.PointLight(0x2f7dff, 3.6, 8.5);
        this.blueGlow.position.set(0, -0.4, 2.2);
        this.scene.add(this.blueGlow);
    }

    addFloor() {
        const shadow = new THREE.Mesh(
            new THREE.CircleGeometry(1.95, 72),
            new THREE.MeshBasicMaterial({
                color: 0x0b63f6,
                transparent: true,
                opacity: 0.18,
                depthWrite: false,
            })
        );
        shadow.rotation.x = -Math.PI / 2;
        shadow.position.y = -1.62;
        shadow.scale.set(1.35, 0.44, 1);
        this.scene.add(shadow);
        this.glowDisc = shadow;

        const floor = new THREE.Mesh(
            new THREE.PlaneGeometry(8, 8),
            new THREE.MeshPhysicalMaterial({
                color: 0xf8fbff,
                roughness: 0.22,
                metalness: 0,
                transmission: 0.08,
                transparent: true,
                opacity: 0.32,
                clearcoat: 1,
                clearcoatRoughness: 0.26,
            })
        );
        floor.rotation.x = -Math.PI / 2;
        floor.position.y = -1.72;
        floor.receiveShadow = true;
        this.scene.add(floor);
    }

    addParticles() {
        const count = window.innerWidth < 760 ? 34 : 58;
        const positions = new Float32Array(count * 3);

        for (let index = 0; index < count; index += 1) {
            positions[index * 3] = (Math.random() - 0.5) * 8;
            positions[index * 3 + 1] = (Math.random() - 0.5) * 4.7;
            positions[index * 3 + 2] = (Math.random() - 0.5) * 5;
        }

        const geometry = new THREE.BufferGeometry();
        geometry.setAttribute('position', new THREE.BufferAttribute(positions, 3));

        this.particles = new THREE.Points(
            geometry,
            new THREE.PointsMaterial({
                color: 0x75aaff,
                size: 0.018,
                transparent: true,
                opacity: 0.34,
                depthWrite: false,
            })
        );
        this.scene.add(this.particles);
    }

    loadModel() {
        const loader = new GLTFLoader();

        loader.load(
            this.modelUrl,
            (gltf) => {
                if (this.disposed) return;

                this.model = gltf.scene;
                this.prepareModel(this.model);
                this.scene.add(this.model);
                this.hideLoader();
                this.playIntro();
            },
            (event) => {
                if (!this.progressEl || !event.total) return;

                const progress = Math.min(100, Math.round((event.loaded / event.total) * 100));
                this.progressEl.style.transform = `scaleX(${progress / 100})`;
            },
            () => {
                this.hideLoader();
                this.showFallback();
            }
        );
    }

    prepareModel(model) {
        const box = new THREE.Box3().setFromObject(model);
        const center = box.getCenter(new THREE.Vector3());
        const size = box.getSize(new THREE.Vector3());
        const maxAxis = Math.max(size.x, size.y, size.z) || 1;
        const scale = window.innerWidth < 760 ? 2.4 / maxAxis : 3.12 / maxAxis;

        model.position.sub(center);
        model.scale.setScalar(scale);
        model.rotation.set(0.12, -0.38, -0.04);
        model.position.y = -0.08;

        model.traverse((child) => {
            if (!child.isMesh) return;

            child.castShadow = true;
            child.receiveShadow = true;

            if (child.material) {
                child.material.envMapIntensity = 1.3;
                child.material.needsUpdate = true;
            }
        });
    }

    playIntro() {
        if (prefersReducedMotion || !this.model) return;

        gsap.fromTo(
            this.model.scale,
            { x: this.model.scale.x * 0.82, y: this.model.scale.y * 0.82, z: this.model.scale.z * 0.82 },
            { x: this.model.scale.x, y: this.model.scale.y, z: this.model.scale.z, duration: 1.4, ease: 'expo.out' }
        );
        gsap.fromTo(this.model.position, { y: -0.55 }, { y: -0.08, duration: 1.55, ease: 'power3.out' });
    }

    hideLoader() {
        this.loaderEl?.classList.add('is-loaded');
    }

    showFallback() {
        this.fallbackEl?.classList.add('is-visible');
    }

    bindEvents() {
        this.resizeHandler = () => this.resize();
        this.pointerHandler = (event) => {
            const rect = this.root.getBoundingClientRect();
            this.pointer.targetX = ((event.clientX - rect.left) / rect.width - 0.5) * 2;
            this.pointer.targetY = ((event.clientY - rect.top) / rect.height - 0.5) * 2;
        };
        this.leaveHandler = () => {
            this.pointer.targetX = 0;
            this.pointer.targetY = 0;
        };

        window.addEventListener('resize', this.resizeHandler, { passive: true });
        this.root.addEventListener('pointermove', this.pointerHandler, { passive: true });
        this.root.addEventListener('pointerleave', this.leaveHandler, { passive: true });
    }

    resize() {
        const rect = this.root.getBoundingClientRect();
        const width = Math.max(320, rect.width);
        const height = Math.max(360, rect.height);

        this.camera.aspect = width / height;
        this.camera.updateProjectionMatrix();
        this.renderer.setSize(width, height, false);
        this.composer.setSize(width, height);
        this.bloomPass.setSize(width, height);
    }

    animate() {
        if (this.disposed) return;

        const elapsed = this.clock.getElapsedTime();
        const delta = this.clock.getDelta();
        const lerpSpeed = Math.min(1, delta * 4.8);

        this.pointer.x += (this.pointer.targetX - this.pointer.x) * lerpSpeed;
        this.pointer.y += (this.pointer.targetY - this.pointer.y) * lerpSpeed;

        if (this.model && !prefersReducedMotion) {
            this.model.rotation.y += 0.0045;
            this.model.rotation.x = 0.12 + this.pointer.y * 0.08;
            this.model.rotation.z = -0.04 - this.pointer.x * 0.06;
            this.model.position.y = -0.08 + Math.sin(elapsed * 1.25) * 0.095;
            this.model.position.x = this.pointer.x * 0.13;
        }

        if (this.glowDisc) {
            this.glowDisc.material.opacity = 0.15 + Math.sin(elapsed * 2.2) * 0.035;
            this.glowDisc.scale.x = 1.35 + Math.sin(elapsed * 1.6) * 0.08;
        }

        if (this.blueGlow) {
            this.blueGlow.intensity = 3.3 + Math.sin(elapsed * 1.8) * 0.45;
            this.blueGlow.position.x = this.pointer.x * 0.55;
        }

        if (this.particles && !prefersReducedMotion) {
            this.particles.rotation.y += 0.0008;
            this.particles.rotation.x = this.pointer.y * 0.025;
        }

        this.camera.position.x += (this.pointer.x * 0.28 - this.camera.position.x) * 0.035;
        this.camera.position.y += (0.34 - this.pointer.y * 0.13 - this.camera.position.y) * 0.035;
        this.camera.lookAt(0, -0.1, 0);

        this.composer.render();
        this.frameId = window.requestAnimationFrame(() => this.animate());
    }

    destroy() {
        this.disposed = true;
        window.cancelAnimationFrame(this.frameId);
        window.removeEventListener('resize', this.resizeHandler);
        this.root.removeEventListener('pointermove', this.pointerHandler);
        this.root.removeEventListener('pointerleave', this.leaveHandler);
        this.scene?.traverse((object) => {
            if (object.geometry) object.geometry.dispose();
            if (object.material) {
                const materials = Array.isArray(object.material) ? object.material : [object.material];
                materials.forEach((material) => material.dispose());
            }
        });
        this.composer?.dispose();
        this.renderer?.dispose();
    }
}

function animateHeroCopy() {
    const hero = document.querySelector('[data-premium-hero]');
    if (!hero || prefersReducedMotion) return;

    gsap.from(hero.querySelectorAll('[data-hero-reveal]'), {
        autoAlpha: 0,
        y: 28,
        duration: 0.9,
        ease: 'power3.out',
        stagger: 0.11,
        delay: 0.12,
    });

    gsap.from(hero.querySelector('[data-showcase-shell]'), {
        autoAlpha: 0,
        y: 34,
        scale: 0.96,
        duration: 1.2,
        ease: 'power3.out',
        delay: 0.28,
    });
}

export function initVapeShowcase() {
    const roots = [...document.querySelectorAll('[data-vape-showcase]')];
    const instances = roots.map((root) => new VapeShowcase(root));
    animateHeroCopy();

    window.addEventListener('beforeunload', () => {
        instances.forEach((instance) => instance.destroy());
    });
}
