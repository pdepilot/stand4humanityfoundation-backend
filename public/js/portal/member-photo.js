(() => {
    const desk = document.querySelector('[data-member-photo]');

    if (!desk) {
        return;
    }

    const frame = desk.querySelector('[data-photo-frame]');
    const preview = desk.querySelector('[data-photo-preview]');
    const placeholder = desk.querySelector('[data-photo-placeholder]');
    const video = desk.querySelector('[data-photo-video]');
    const input = desk.querySelector('[data-photo-input]');
    const status = desk.querySelector('[data-photo-status]');
    const uploadButton = desk.querySelector('[data-photo-upload]');
    const cameraButton = desk.querySelector('[data-photo-camera]');
    const captureButton = desk.querySelector('[data-photo-capture]');
    const cancelButton = desk.querySelector('[data-photo-cancel]');

    let stream = null;

    const setStatus = (message) => {
        if (status) {
            status.textContent = message;
        }
    };

    const stopCamera = () => {
        if (stream) {
            stream.getTracks().forEach((track) => track.stop());
            stream = null;
        }

        if (video) {
            video.srcObject = null;
            video.hidden = true;
        }

        frame?.classList.remove('is-live');
        captureButton.hidden = true;
        cancelButton.hidden = true;
        uploadButton.hidden = false;
        cameraButton.hidden = false;
    };

    const showPreview = (url) => {
        preview.src = url;
        preview.hidden = false;

        if (placeholder) {
            placeholder.hidden = true;
        }
    };

    uploadButton.addEventListener('click', () => {
        stopCamera();
        input.click();
    });

    input.addEventListener('change', () => {
        const file = input.files?.[0];

        if (!file) {
            return;
        }

        showPreview(URL.createObjectURL(file));
        setStatus('Photo ready. Save the member to keep it.');
    });

    cameraButton.addEventListener('click', async () => {
        if (!navigator.mediaDevices?.getUserMedia) {
            setStatus('This browser cannot open the camera. Upload a photo instead.');

            return;
        }

        try {
            stream = await navigator.mediaDevices.getUserMedia({
                video: { facingMode: 'user' },
                audio: false,
            });
        } catch (error) {
            setStatus('The camera could not open. Allow camera access, or upload a photo.');

            return;
        }

        video.srcObject = stream;
        video.hidden = false;
        frame.classList.add('is-live');
        uploadButton.hidden = true;
        cameraButton.hidden = true;
        captureButton.hidden = false;
        cancelButton.hidden = false;
        setStatus('Look at the camera, then capture the portrait.');
    });

    cancelButton.addEventListener('click', () => {
        stopCamera();
        setStatus('Camera closed. Upload a photo or open the camera again.');
    });

    captureButton.addEventListener('click', () => {
        if (!stream || video.videoWidth === 0) {
            setStatus('The camera is not ready yet.');

            return;
        }

        const canvas = document.createElement('canvas');
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;

        const context = canvas.getContext('2d');

        if (!context) {
            setStatus('The portrait could not be captured. Upload a photo instead.');

            return;
        }

        context.translate(canvas.width, 0);
        context.scale(-1, 1);
        context.drawImage(video, 0, 0);

        canvas.toBlob((blob) => {
            if (!blob) {
                setStatus('The portrait could not be captured. Upload a photo instead.');

                return;
            }

            const file = new File([blob], 'member-portrait.jpg', { type: 'image/jpeg' });
            const transfer = new DataTransfer();
            transfer.items.add(file);
            input.files = transfer.files;

            showPreview(URL.createObjectURL(file));
            stopCamera();
            setStatus('Portrait captured. Save the member to keep it.');
        }, 'image/jpeg', 0.92);
    });

    window.addEventListener('pagehide', stopCamera);
})();
