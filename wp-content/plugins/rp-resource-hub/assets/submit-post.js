(function () {
	'use strict';

	const form = document.querySelector('.rp-submit-post-form');
	if (!form) {
		return;
	}

	const title = form.querySelector('#rp_title');
	const excerpt = form.querySelector('#rp_excerpt');
	const category = form.querySelector('#rp_category');
	const imageInput = form.querySelector('#rp_featured_image');
	const imageAlt = form.querySelector('#rp_featured_image_alt');
	const imagePreview = form.querySelector('#rp-image-preview');
	const imageError = form.querySelector('#rp-image-error');
	const dropzone = form.querySelector('.rp-image-dropzone');
	const submitButton = form.querySelector('button[type="submit"]');
	const autosaveStatus = form.querySelector('#rp-autosave-status');
	const draftKey = form.dataset.autosaveKey;
	let imageUrl = '';
	let saveTimer;

	function getEditor() {
		return window.tinymce ? window.tinymce.get('rp_content') : null;
	}

	function getContent() {
		const editor = getEditor();
		return editor ? editor.getContent() : form.querySelector('#rp_content').value;
	}

	function setContent(content) {
		const editor = getEditor();
		if (editor) {
			editor.setContent(content || '');
		} else {
			form.querySelector('#rp_content').value = content || '';
		}
	}

	function textFromHtml(html) {
		const container = document.createElement('div');
		container.innerHTML = html;
		return (container.textContent || '').trim();
	}

	function updateCounts() {
		const words = textFromHtml(getContent()).match(/\S+/g) || [];
		form.querySelector('#rp-word-count').textContent = words.length;
		form.querySelector('#rp-reading-time').textContent = words.length < 200
			? 'Less than 1 min read'
			: Math.ceil(words.length / 200) + ' min read';
		form.querySelector('#rp-title-count').textContent = title.value.length + '/160';
		form.querySelector('#rp-excerpt-count').textContent = excerpt.value.length + '/320';
	}

	function saveDraft() {
		if (!draftKey) {
			return;
		}
		try {
			window.localStorage.setItem(draftKey, JSON.stringify({
				title: title.value,
				excerpt: excerpt.value,
				category: category.value,
				content: getContent(),
				imageAlt: imageAlt.value,
				savedAt: Date.now()
			}));
			autosaveStatus.textContent = 'Draft saved locally';
		} catch (error) {
			autosaveStatus.textContent = 'Local autosave unavailable';
		}
	}

	function queueSave() {
		window.clearTimeout(saveTimer);
		autosaveStatus.textContent = 'Saving draft…';
		form.querySelector('#rp-content-error').hidden = true;
		saveTimer = window.setTimeout(saveDraft, 700);
		updateCounts();
	}

	function restoreDraft() {
		if (!draftKey || title.value || excerpt.value || getContent()) {
			return;
		}

		try {
			const draft = JSON.parse(window.localStorage.getItem(draftKey));
			if (!draft) {
				return;
			}
			title.value = draft.title || '';
			excerpt.value = draft.excerpt || '';
			category.value = draft.category || '';
			imageAlt.value = draft.imageAlt || '';
			setContent(draft.content);
			autosaveStatus.textContent = 'Local draft restored';
		} catch (error) {
			window.localStorage.removeItem(draftKey);
		}
		updateCounts();
	}

	function validateImage(file) {
		const allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
		let message = '';
		if (!allowedTypes.includes(file.type)) {
			message = 'Choose a JPEG, PNG, or WebP image.';
		} else if (file.size > 5 * 1024 * 1024) {
			message = 'The image must be 5MB or smaller.';
		}

		imageError.textContent = message;
		imageError.hidden = !message;
		imageInput.setCustomValidity(message);
		return !message;
	}

	function showImage(file) {
		if (!file || !validateImage(file)) {
			imagePreview.hidden = true;
			return;
		}
		if (imageUrl) {
			URL.revokeObjectURL(imageUrl);
		}
		imageUrl = URL.createObjectURL(file);
		imagePreview.src = imageUrl;
		imagePreview.alt = imageAlt.value;
		imagePreview.hidden = false;
	}

	function openPreview() {
		const dialog = document.querySelector('#rp-post-preview');
		document.querySelector('#rp-preview-title').textContent = title.value || 'Untitled post';
		document.querySelector('#rp-preview-excerpt').textContent = excerpt.value;
		document.querySelector('#rp-preview-body').innerHTML = getContent() || '<p>Start writing to preview your post.</p>';
		const previewImage = document.querySelector('#rp-preview-image');
		previewImage.src = imageUrl;
		previewImage.alt = imageAlt.value;
		previewImage.hidden = !imageUrl;
		if (typeof dialog.showModal === 'function') {
			dialog.showModal();
		} else {
			dialog.setAttribute('open', '');
		}
	}

	[title, excerpt, category, imageAlt].forEach(function (field) {
		field.addEventListener('input', queueSave);
		field.addEventListener('change', queueSave);
	});

	imageInput.addEventListener('change', function () {
		showImage(imageInput.files[0]);
	});

	['dragenter', 'dragover'].forEach(function (eventName) {
		dropzone.addEventListener(eventName, function (event) {
			event.preventDefault();
			dropzone.classList.add('is-dragging');
		});
	});

	['dragleave', 'drop'].forEach(function (eventName) {
		dropzone.addEventListener(eventName, function (event) {
			event.preventDefault();
			dropzone.classList.remove('is-dragging');
		});
	});

	dropzone.addEventListener('drop', function (event) {
		const file = event.dataTransfer.files[0];
		if (file && validateImage(file)) {
			const transfer = new DataTransfer();
			transfer.items.add(file);
			imageInput.files = transfer.files;
			showImage(file);
		}
	});

	document.querySelector('#rp-preview-post').addEventListener('click', openPreview);
	document.querySelector('#rp-close-preview').addEventListener('click', function () {
		document.querySelector('#rp-post-preview').close();
	});

	form.addEventListener('submit', function (event) {
		const editor = getEditor();
		if (editor) {
			editor.save();
		}
		if (!textFromHtml(getContent())) {
			event.preventDefault();
			form.querySelector('#rp-content-error').hidden = false;
			editor && editor.focus();
			return;
		}
		if (!form.checkValidity()) {
			return;
		}
		submitButton.disabled = true;
		submitButton.textContent = submitButton.dataset.busyLabel;
		try {
			window.localStorage.removeItem(draftKey);
		} catch (error) {
			// Submission can continue when local storage is unavailable.
		}
	});

	function connectEditor(attempts) {
		const editor = getEditor();
		if (editor) {
			editor.on('input change undo redo', queueSave);
			restoreDraft();
			updateCounts();
			return;
		}
		if (attempts > 0) {
			window.setTimeout(function () { connectEditor(attempts - 1); }, 100);
		}
	}

	connectEditor(50);
}());
