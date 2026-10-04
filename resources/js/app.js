

import Alpine from 'alpinejs';

window.Alpine = Alpine;

/**
 * Konversi file gambar ke format WebP di sisi klien (browser) untuk mempercepat proses upload dan optimasi web.
 *
 * @param {File} file File gambar asli
 * @param {number} quality Kualitas kompresi (0.1 - 1.0, default 0.82)
 * @param {number} maxWidth Lebar maksimal dalam pixel (default 1920)
 * @returns {Promise<File>} File terkonversi dalam format WebP
 */
window.convertToWebP = async function(file, quality = 0.82, maxWidth = 1920) {
    if (!file || !file.type.startsWith('image/') || file.type === 'image/webp') {
        return file;
    }

    return new Promise((resolve) => {
        const reader = new FileReader();
        reader.onload = (e) => {
            const img = new Image();
            img.onload = () => {
                let width = img.width;
                let height = img.height;

                if (width > maxWidth) {
                    height = Math.round((height / width) * maxWidth);
                    width = maxWidth;
                }

                const canvas = document.createElement('canvas');
                canvas.width = width;
                canvas.height = height;
                const ctx = canvas.getContext('2d');
                ctx.drawImage(img, 0, 0, width, height);

                canvas.toBlob((blob) => {
                    if (blob) {
                        const newName = file.name.replace(/\.[^/.]+$/, "") + ".webp";
                        const webpFile = new File([blob], newName, {
                            type: 'image/webp',
                            lastModified: Date.now()
                        });
                        resolve(webpFile);
                    } else {
                        resolve(file);
                    }
                }, 'image/webp', quality);
            };
            img.onerror = () => resolve(file);
            img.src = e.target.result;
        };
        reader.onerror = () => resolve(file);
        reader.readAsDataURL(file);
    });
};

Alpine.start();
