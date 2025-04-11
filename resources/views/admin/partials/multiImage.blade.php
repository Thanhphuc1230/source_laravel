<style>
    .container {
      margin: 0 auto;
      background-color: white;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    #drop-area {
      border: 2px dashed #ccc;
      border-radius: 20px;
      width: 100%;
      padding: 20px;
      text-align: center;
      margin-bottom: 20px;
    }

    #drop-area.highlight {
      border-color: purple;
    }

    .my-form {
      margin-bottom: 10px;
    }

    #gallery {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
    }

    .thumbnail {
      max-width: 150px;
      max-height: 150px;
      margin: 10px;
      padding: 5px;
      border: 1px solid #ddd;
      border-radius: 4px;
    }

    #fileElem {
      display: none;
    }

    .button {
      display: inline-block;
      padding: 10px 20px;
      background-color: #4CAF50;
      color: white;
      cursor: pointer;
      border-radius: 5px;
    }

    .button:hover {
      background-color: #45a049;
    }

    #upload-progress {
      margin-top: 20px;
    }

    .progress-bar {
      width: 100%;
      background-color: #e0e0e0;
      padding: 3px;
      border-radius: 3px;
      box-shadow: inset 0 1px 3px rgba(0, 0, 0, .2);
    }

    .progress-bar-fill {
      display: block;
      height: 22px;
      background-color: #659cef;
      border-radius: 3px;
      transition: width 500ms ease-in-out;
    }

    .thumbnail-container {
      position: relative;
      display: inline-block;
      margin: 10px;
    }

    .delete-btn {
      position: absolute;
      top: 5px;
      right: 5px;
      background-color: rgba(255, 0, 0, 0.7);
      color: white;
      border: none;
      border-radius: 50%;
      width: 20px;
      height: 20px;
      font-size: 12px;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .delete-btn:hover {
      background-color: rgba(255, 0, 0, 1);
    }

    #preview-container {
      display: flex;
      flex-wrap: wrap;
      gap: 10px;
      justify-content: flex-start;
    }

    .preview-item {
      width: calc(25% - 7.5px); /* 25% width minus gap */
      max-width: 150px;
      position: relative;
    }

    .preview-image {
      width: 100%;
      height: 150px;
      object-fit: contain;
    }

    .remove-btn {
      position: absolute;
      top: 5px;
      right: 5px;
      background-color: rgba(255, 0, 0, 0.7);
      color: white;
      border: none;
      border-radius: 50%;
      width: 20px;
      height: 20px;
      font-size: 12px;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .remove-btn:hover {
      background-color: rgba(255, 0, 0, 1);
    }
  </style>
</head>

<body>
  <div class="container">
    <div id="drop-area">
      <form class="my-form">
        <p>Kéo thả file vào đây hoặc click để chọn file</p>
        <input type="file" id="fileElem" name="image_detail[]" multiple accept="image/*" onchange="handleFiles(this.files)">
        <label class="button" for="fileElem">Chọn file</label>
      </form>
    </div>
    <div id="preview-container"></div>
    <div id="upload-status"></div>
  </div>

  <script>
    let dropArea = document.getElementById('drop-area');

    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
      dropArea.addEventListener(eventName, preventDefaults, false);
    });

    function preventDefaults(e) {
      e.preventDefault();
      e.stopPropagation();
    }

    ['dragenter', 'dragover'].forEach(eventName => {
      dropArea.addEventListener(eventName, highlight, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
      dropArea.addEventListener(eventName, unhighlight, false);
    });

    function highlight(e) {
      dropArea.classList.add('highlight');
    }

    function unhighlight(e) {
      dropArea.classList.remove('highlight');
    }

    dropArea.addEventListener('drop', handleDrop, false);

    function handleDrop(e) {
      let dt = e.dataTransfer;
      let files = dt.files;
      handleFiles(files);
    }

    function handleFiles(files) {
      // Không xóa hình ảnh cũ
      files = [...files];
      files.forEach(previewFile);
    }

    function previewFile(file) {
      let reader = new FileReader();
      reader.readAsDataURL(file);
      reader.onloadend = function() {
        let container = document.createElement('div');
        container.className = 'preview-item';

        let img = document.createElement('img');
        img.src = reader.result;
        img.className = 'preview-image';

        let deleteBtn = document.createElement('button');
        deleteBtn.className = 'remove-btn';
        deleteBtn.innerHTML = '×';
        deleteBtn.onclick = function() {
          container.remove();
        };

        container.appendChild(img);
        container.appendChild(deleteBtn);
        document.getElementById('preview-container').appendChild(container);
      }
    }

    // Hàm hiển thị hình ảnh cũ
    function displayExistingImages(existingImages) {
      existingImages.forEach(image => {
        let container = document.createElement('div');
        container.className = 'preview-item';

        let img = document.createElement('img');
        img.src = '{{ asset('images/products/') }}/' + image; // Đường dẫn đến hình ảnh cũ
        img.className = 'preview-image';

        let deleteBtn = document.createElement('button');
        deleteBtn.className = 'remove-btn';
        deleteBtn.innerHTML = '×';
        deleteBtn.onclick = function() {
          container.remove();
        };

        container.appendChild(img);
        container.appendChild(deleteBtn);
        document.getElementById('preview-container').appendChild(container);
      });
    }


  </script>

     {{-- upload file images --}}
     <script type="text/javascript">
      $(document).ready(function() {
          $(".btn-add-image").click(function() {
              $('#file_upload').trigger('click');
          });

          $('.list-input-hidden-upload').on('change', '#file_upload', function(event) {
              let today = new Date();
              let time = today.getTime();
              let image = event.target.files[0];
              let file_name = event.target.files[0].name;
              let box_image = $('<div class="box-image"></div>');
              box_image.append('<img src="' + URL.createObjectURL(image) +
                  '" class="picture-box" width="200px">');
              box_image.append('<div class="wrap-btn-delete"><span data-id=' + time +
                  ' class="btn-delete-image">x</span></div>');
              $(".list-images").append(box_image);

              $(this).removeAttr('id');
              $(this).attr('id', time);
              let input_type_file =
                  '<input type="file" name="image_detail[]" id="file_upload" class="myfrm form-control hidden">';
              $('.list-input-hidden-upload').append(input_type_file);
          });

          $(".list-images").on('click', '.btn-delete-image', function() {
              let id = $(this).data('id');
              $('#' + id).remove();
              $(this).parents('.box-image').remove();
          });
      });
  </script>