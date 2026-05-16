import fs from 'node:fs';
import path from 'node:path';
import * as THREE from 'three';
import { GLTFExporter } from 'three/examples/jsm/exporters/GLTFExporter.js';

globalThis.FileReader = class {
    readAsArrayBuffer(blob) {
        blob.arrayBuffer().then((buffer) => {
            this.result = buffer;
            this.onloadend?.();
        });
    }
};

const outDir = path.resolve('public/models');
const outFile = path.join(outDir, 'xros.glb');
fs.mkdirSync(outDir, { recursive: true });

function roundedBox(width, height, depth, radius, smoothness = 12) {
    const x = -width / 2;
    const y = -height / 2;
    const shape = new THREE.Shape();

    shape.moveTo(x + radius, y);
    shape.lineTo(x + width - radius, y);
    shape.quadraticCurveTo(x + width, y, x + width, y + radius);
    shape.lineTo(x + width, y + height - radius);
    shape.quadraticCurveTo(x + width, y + height, x + width - radius, y + height);
    shape.lineTo(x + radius, y + height);
    shape.quadraticCurveTo(x, y + height, x, y + height - radius);
    shape.lineTo(x, y + radius);
    shape.quadraticCurveTo(x, y, x + radius, y);

    const geometry = new THREE.ExtrudeGeometry(shape, {
        depth,
        bevelEnabled: true,
        bevelSegments: smoothness,
        bevelSize: Math.min(radius * 0.45, depth * 0.22),
        bevelThickness: Math.min(radius * 0.5, depth * 0.26),
        curveSegments: smoothness,
        steps: 1,
    });

    geometry.center();
    geometry.computeVertexNormals();
    return geometry;
}

function add(mesh, parent, position = [0, 0, 0], rotation = [0, 0, 0]) {
    mesh.position.set(...position);
    mesh.rotation.set(...rotation);
    mesh.castShadow = true;
    mesh.receiveShadow = true;
    parent.add(mesh);
    return mesh;
}

const scene = new THREE.Scene();
scene.name = 'Puffcart XROS Inspired Pod Device';

const root = new THREE.Group();
root.name = 'xros_realistic_device';
scene.add(root);

const bodyMat = new THREE.MeshPhysicalMaterial({
    name: 'brushed blue anodized metal',
    color: new THREE.Color('#1f69d8'),
    metalness: 0.82,
    roughness: 0.24,
    clearcoat: 1,
    clearcoatRoughness: 0.18,
});

const sideMat = new THREE.MeshPhysicalMaterial({
    name: 'polished chamfered edge',
    color: new THREE.Color('#dbeafe'),
    metalness: 0.95,
    roughness: 0.16,
    clearcoat: 1,
});

const blackMat = new THREE.MeshPhysicalMaterial({
    name: 'glossy black control strip',
    color: new THREE.Color('#07111f'),
    metalness: 0.18,
    roughness: 0.12,
    clearcoat: 1,
    clearcoatRoughness: 0.08,
});

const podMat = new THREE.MeshPhysicalMaterial({
    name: 'smoked translucent pod',
    color: new THREE.Color('#2b3445'),
    metalness: 0.02,
    roughness: 0.08,
    transmission: 0.36,
    transparent: true,
    opacity: 0.68,
    clearcoat: 1,
    ior: 1.45,
});

const glassMat = new THREE.MeshPhysicalMaterial({
    name: 'soft glass highlight',
    color: new THREE.Color('#ffffff'),
    metalness: 0,
    roughness: 0.04,
    transparent: true,
    opacity: 0.42,
    clearcoat: 1,
});

const ledMat = new THREE.MeshPhysicalMaterial({
    name: 'blue led indicator',
    color: new THREE.Color('#3b82f6'),
    emissive: new THREE.Color('#1d4ed8'),
    emissiveIntensity: 2.6,
    metalness: 0.1,
    roughness: 0.2,
});

const whiteMat = new THREE.MeshPhysicalMaterial({
    name: 'white printed branding',
    color: new THREE.Color('#f8fbff'),
    metalness: 0,
    roughness: 0.38,
});

const body = add(
    new THREE.Mesh(roundedBox(0.86, 4.35, 0.36, 0.18, 16), bodyMat),
    root,
    [0, -0.18, 0]
);

const frontPanel = add(
    new THREE.Mesh(roundedBox(0.12, 2.08, 0.035, 0.035, 8), blackMat),
    root,
    [0, -0.2, 0.205]
);

const sideLeft = add(
    new THREE.Mesh(roundedBox(0.045, 3.86, 0.028, 0.02, 6), sideMat),
    root,
    [-0.455, -0.2, 0.19]
);
const sideRight = sideLeft.clone();
sideRight.position.x = 0.455;
root.add(sideRight);

const pod = add(
    new THREE.Mesh(roundedBox(0.78, 1.05, 0.42, 0.2, 16), podMat),
    root,
    [0, 2.48, 0]
);

const mouth = add(
    new THREE.Mesh(roundedBox(0.58, 0.52, 0.34, 0.16, 16), blackMat),
    root,
    [0, 3.2, 0]
);

const podWindow = add(
    new THREE.Mesh(roundedBox(0.42, 0.72, 0.018, 0.08, 10), glassMat),
    root,
    [0, 2.42, 0.24]
);

const button = add(
    new THREE.Mesh(new THREE.CylinderGeometry(0.145, 0.145, 0.04, 40), ledMat),
    root,
    [0, -1.86, 0.235],
    [Math.PI / 2, 0, 0]
);

const lowerBadge = add(
    new THREE.Mesh(roundedBox(0.42, 0.08, 0.016, 0.02, 5), whiteMat),
    root,
    [0, -1.35, 0.244]
);

const brandGroup = new THREE.Group();
brandGroup.name = 'XROS logo marks';
root.add(brandGroup);

const letterWidths = [0.1, 0.1, 0.13, 0.1];
const letters = ['X', 'R', 'O', 'S'];
letters.forEach((letter, index) => {
    const mark = new THREE.Mesh(roundedBox(letterWidths[index], 0.055, 0.014, 0.012, 4), whiteMat);
    mark.name = `brand_${letter}`;
    mark.position.set(-0.23 + index * 0.15, -0.78, 0.252);
    brandGroup.add(mark);
});

const highlightTop = add(
    new THREE.Mesh(roundedBox(0.34, 0.08, 0.012, 0.018, 6), glassMat),
    root,
    [-0.16, 2.76, 0.255],
    [0, 0, -0.06]
);

const bevelGlow = add(
    new THREE.Mesh(roundedBox(0.72, 0.035, 0.012, 0.014, 5), glassMat),
    root,
    [0, 1.42, 0.247]
);

root.rotation.set(0.08, -0.38, -0.08);
root.scale.setScalar(1.16);

const key = new THREE.DirectionalLight(0xffffff, 3);
key.position.set(-3, 5, 4);
scene.add(key);

const exporter = new GLTFExporter();
const arrayBuffer = await new Promise((resolve, reject) => {
    exporter.parse(
        scene,
        (result) => resolve(result),
        (error) => reject(error),
        {
            binary: true,
            onlyVisible: true,
            trs: false,
        }
    );
});

fs.writeFileSync(outFile, Buffer.from(arrayBuffer));
console.log(`Wrote ${outFile} (${fs.statSync(outFile).size} bytes)`);
