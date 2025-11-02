 <div class="modal fade" id="image-ditor-modal" tabindex="-1" aria-hidden="true">
     <div class="modal-dialog modal-fullscreen">
         <div class="modal-content">
             <div class="modal-header">
                 <h5 class="modal-title">{{ __('form.image_editor') }}</h5>
                 <button class="btn-close" data-bs-dismiss="modal"></button>
             </div>
             <div class="modal-body">
                 <div id="editorWrap">
                     <div id="tuiEditor"></div>
                 </div>
             </div>
         </div>
     </div>
     <style>
         #image-ditor-modal .modal-fullscreen .modal-content,
         #image-ditor-modal .modal-fullscreen .modal-body {
             height: 100%;
         }

         #image-ditor-modal .modal-body {
             display: flex;
             flex-direction: column;
             gap: 10px;
         }

         #editorWrap {
             flex: 1;
             min-height: 0;
             border: 1px solid #e5e7eb;
         }

         #tuiEditor {
             width: 100%;
             height: 100%;
             position: relative;
         }

         #tuiEditor .tui-image-editor-help-menu {
             display: flex;
             align-items: center;
             justify-content: center;
         }

         #tuiEditor .tie-crop-button.action {
             display: flex;
             align-items: center;
             gap: 5px;
             justify-content: center;
         }

         #tuiEditor .tie-crop-button.action .tui-image-editor-button {
             display: flex;
             align-items: center;
             gap: 5px;
         }

         #tuiEditor .tui-image-editor-header {
             visibility: hidden;
         }

         #tuiEditor .tui-image-editor-button.apply {
             display: flex;
             align-items: center;
         }
         #tuiEditor .tie-mask-apply.apply {
             display: flex;
             align-items: center;
             justify-content: center
         }
     </style>
 </div>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/tui-code-snippet/2.3.2/tui-code-snippet.min.js"></script>
 <script src="https://cdn.jsdelivr.net/npm/fabric@4.6.0/dist/fabric.min.js"></script>
 <script src="https://cdn.jsdelivr.net/npm/tui-color-picker@2.2.8/dist/tui-color-picker.min.js"></script>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/tui-image-editor/3.15.3/tui-image-editor.min.js"></script>
 <script>
     const previewZoomButtonClasses = {
         rotate: 'btn btn-light btn-icon btn-sm',
         toggleheader: 'btn btn-light btn-icon btn-header-toggle btn-sm',
         fullscreen: 'btn btn-light btn-icon btn-sm',
         borderless: 'btn btn-light btn-icon btn-sm',
         close: 'btn btn-light btn-icon btn-sm'
     };
     const previewZoomButtonIcons = {
         prev: document.dir == 'rtl' ? '<i class="fa-solid fa-arrow-right"></i>' : '<i class="fa-solid fa-arrow-left"></i>',
         next: document.dir == 'rtl' ? '<i class="fa-solid fa-arrow-left"></i>' : '<i class="fa-solid fa-arrow-right"></i>',
         rotate: '<i class="fa-duotone fa-solid fa-rotate-right"></i>',
         toggleheader: '<i class="fa-duotone fa-solid fa-arrow-down-arrow-up"></i>',
         fullscreen: '<i class="fa-regular fa-expand-wide"></i>',
         borderless: '<i class="fa-regular fa-compress-wide"></i>',
         close: '<i class="fa-regular fa-xmark"></i>'
     };
     const fileActionSettings = {
         zoomClass: '',
         zoomIcon: '<i class="fa-solid fa-magnifying-glass-plus"></i>',
         dragClass: 'p-2',
         dragIcon: '<i class="fa-solid fa-dots-six"></i>',
         removeClass: '',
         removeErrorClass: 'text-danger',
         removeIcon: '<i class="fa-light fa-trash"></i>',
         indicatorNew: '<i class="fa-solid fa-file-plus text-success"></i>',
         indicatorSuccess: '<i class="fa-solid fa-check file-icon-large text-success"></i>',
         indicatorError: '<i class="fa-solid fa-x text-danger"></i>',
         indicatorLoading: '<i class="fa-light fa-loader text-muted spinner"></i>'
     };
     $('.file-input').fileinput({
         browseLabel: 'Browse',
         browseIcon: '<i class="fa-light fa-clone-plus me-2"></i>',
         uploadIcon: '<i class="fa-light fa-cloud-arrow-up me-2"></i>',
         removeIcon: '<i class="fa-light fa-x fs-base me-2"></i>',
         layoutTemplates: {
             icon: '<i class="fa-light fa-check"></i>'
         },
         uploadClass: 'btn btn-light',
         removeClass: 'btn btn-light',
         browseClass: 'btn btn-light',
         initialCaption: "No file selected",
         previewZoomButtonClasses: previewZoomButtonClasses,
         previewZoomButtonIcons: previewZoomButtonIcons,
         fileActionSettings: fileActionSettings
     });

     let editor = null;

     function resizeEditor() {
         if (!editor || !editor.ui) return;
         const wrap = document.getElementById("editorWrap");
         editor.ui.resizeEditor({
             uiSize: {
                 width: wrap.clientWidth,
                 height: wrap.clientHeight
             }
         });
     }

     async function loadBlank() {
         const wrap = document.getElementById("editorWrap");
         const w = wrap.clientWidth || 1000;
         const h = wrap.clientHeight || Math.max(window.innerHeight - 220, 600);

         const c = document.createElement("canvas");
         c.width = w;
         c.height = h;
         const g = c.getContext("2d");
         g.fillStyle = "#fff";
         g.fillRect(0, 0, w, h);
         g.strokeStyle = "#f0f0f0";
         g.lineWidth = 1;
         for (let x = 0; x <= w; x += 50) {
             g.beginPath();
             g.moveTo(x, 0);
             g.lineTo(x, h);
             g.stroke();
         }
         for (let y = 0; y <= h; y += 50) {
             g.beginPath();
             g.moveTo(0, y);
             g.lineTo(w, y);
             g.stroke();
         }

         await editor.loadImageFromURL(c.toDataURL("image/png"), "Blank");
         editor.clearUndoStack();
     }

     async function destroyEditor() {
         if (!editor) return;
         try {
             editor.destroy?.();
         } catch (e) {
             console.warn(e);
         }
         editor = null;
     }

     document.addEventListener('DOMContentLoaded', () => {
         const openBtn = document.querySelector('.file-input-open-editor');
         const modalEl = document.getElementById('image-ditor-modal');
         openBtn.addEventListener('click', (e) => {
             e.preventDefault();
             bootstrap.Modal.getOrCreateInstance(modalEl).show();
         });
         modalEl.addEventListener('shown.bs.modal', async () => {
             await destroyEditor();
             editor = new tui.ImageEditor(document.getElementById('tuiEditor'), {
                 includeUI: {
                     menu: ['crop', 'flip', 'rotate', 'draw', 'shape', 'icon', 'text', 'mask', 'filter'],
                     initMenu: 'crop',
                     uiSize: {
                         width: '100%',
                         height: '100%'
                     },
                     menuBarPosition: 'bottom'
                 },
                 usageStatistics: false
             });
             await loadBlank();
             requestAnimationFrame(resizeEditor);
             setTimeout(() => {
                 const loadBtn = document.querySelector('.tui-image-editor-load-btn');
                 if (loadBtn) loadBtn.click();
             }, 200);
         });
         modalEl.addEventListener('hidden.bs.modal', async () => {
             await destroyEditor();
         });
         window.addEventListener('resize', () => {
             if (modalEl.classList.contains('show')) resizeEditor();
         });
     });
 </script>
