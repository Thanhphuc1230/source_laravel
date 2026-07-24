/**
 * System Language Checkbox Enforcement
 * Đảm bảo tối thiểu phải có 1 ngôn ngữ được bật trong cấu hình hệ thống.
 */
document.addEventListener('DOMContentLoaded', function () {
    var checkboxVi = document.getElementById('lang-vi');
    var checkboxEn = document.getElementById('lang-en');

    if (!checkboxVi || !checkboxEn) return;

    function enforceMinActiveLang(event) {
        if (!checkboxVi.checked && !checkboxEn.checked) {
            event.preventDefault();
            event.target.checked = true;
            alert('Hệ thống yêu cầu tối thiểu phải chọn một ngôn ngữ hoạt động.');
        }
    }

    checkboxVi.addEventListener('change', enforceMinActiveLang);
    checkboxEn.addEventListener('change', enforceMinActiveLang);
});
