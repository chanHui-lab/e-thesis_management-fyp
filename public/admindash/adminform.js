Dropzone.autoDiscover = false;

// var previewNode = document.querySelector("#dropzone");
// // previewNode.id = "";
// var previewTemplate = previewNode.parentNode.innerHTML;
// previewNode.parentNode.removeChild(previewNode);

{/* // var myDropzone = new Dropzone("#file_data", { // Make the whole body a dropzone
//   url: "{{ route('template.create') }}", // Set the url
//   // thumbnailWidth: 80,
//   // thumbnailHeight: 80,
//   parallelUploads: 20,
//   // previewTemplate: previewTemplate,
//   autoQueue: false, // Make sure the files aren't queued until manually added
//   previewsContainer: "#file_data",
//   clickable: ".fileinput-button", // Define the element that should be used as click trigger to select files.
//   acceptedFiles: ".pdf, .docx", // Customize accepted file types

// })
// document.body */}
// document.addEventListener("DOMContentLoaded", function() {
//     document.querySelector(".filecancel-button").addEventListener("click", function() {
//         console.log("Button clicked");
//     });
// });
document.addEventListener("DOMContentLoaded", function() {

  var myDropzone = new Dropzone("#dropzone", { // Make the whole body a dropzone
    url: "{{ route('template.store') }}", // Set the url
    thumbnailWidth: 80,
    thumbnailHeight: 80,
    parallelUploads: 20,
    // previewTemplate: previewTemplate,
    autoQueue: false, // Make sure the files aren't queued until manually added
    previewsContainer: "#previews", // Define the container to display the previews
    clickable: ".fileinput-button" // Define the element that should be used as click trigger to select files.
  });

myDropzone.on("addedfile", function(file) {
  // Hookup the start button
  file.previewElement.querySelector(".start").onclick = function() { myDropzone.enqueueFile(file) }
});

// Update the total progress bar
myDropzone.on("totaluploadprogress", function(progress) {
  document.querySelector("#total-progress .progress-bar").style.width = progress + "%"
})

myDropzone.on("sending", function(file) {
  // Show the total progress bar when upload starts
  document.querySelector("#total-progress").style.opacity = "1"
  // And disable the start button
  file.previewElement.querySelector(".start").setAttribute("disabled", "disabled")
})

// Hide the total progress bar when nothing's uploading anymore
myDropzone.on("queuecomplete", function(progress) {
  document.querySelector("#total-progress").style.opacity = "0"
})

// Setup the buttons for all transfers
// The "add files" button doesn't need to be setup because the config
// `clickable` has already been specified.
document.querySelector("#actions .start").onclick = function() {
  myDropzone.enqueueFiles(myDropzone.getFilesWithStatus(Dropzone.ADDED))
}
document.querySelector("#actions .cancel").onclick = function() {
  myDropzone.removeAllFiles(true)
}
});
// DropzoneJS Demo Code End