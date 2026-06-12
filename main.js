document.addEventListener('DOMContentLoaded',()=>{const forms=document.querySelectorAll('.needs-validation');forms.forEach(form=>{form.addEventListener('submit',e=>{if(!form.checkValidity()){e.preventDefault();e.stopPropagation()}form.classList.add('was-validated')})})});
function confirmAction(message){return confirm(message || 'Are you sure you want to continue?');}
