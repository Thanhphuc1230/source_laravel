# Lịch Sử Quyết Định Kiến Trúc & UI/UX (Long-term Memory)

> File này lưu các quyết định quan trọng đã chốt. AI không được tự ý đề xuất phương án ngược lại.

---

## 🎨 UI/UX & STYLING (Cập nhật chuẩn Modern Web 2026)
- **Framework CSS:** Tailwind CSS (compile file local hoặc Play CDN cấu hình chỉn chu).
- **Typography:** Bắt buộc dùng font hiện đại Tiếng Việt: `Inter`, `Be Vietnam Pro`, hoặc `Plus Jakarta Sans`.
- **Phong cách:** Modern Premium Web Design (Thân thiện, bo tròn `rounded-xl`/`2xl`, shadow mượt, hover scale nhẹ 1.03-1.05, phân mảng phối màu Niche Color Palette theo ngành nghề).
- **Icon Safety:** Tuyệt đối ghi đúng class FontAwesome (`fas fa-...`, `fab fa-...`) hoặc SVG Inline để tránh lỗi ô vuông.
- **Animation:** Dùng Intersection Observer JS (`.scroll-anim.fade-up`) tạo hiệu ứng cuộn mượt.
- **Mobile Grid:** Sản phẩm & Tin tức luôn chia 2 cột trên Mobile (`grid-cols-2`).

---

## ⚙️ GIT & COMMIT CONTROL (QUY TẮC SỐ 8)
- KHÔNG BAO GIỜ tự động commit/push code nếu không có yêu cầu trực tiếp từ user trong phiên chat.
- Mỗi lệnh commit từ user chỉ có giá trị cho LẦN ĐÓ cho task hiện tại.
- Mọi task tiếp theo bắt buộc phải HỎI XIN PHÉP user trước khi commit (`"Tôi đã hoàn thành task [Tên Task]. Bạn có muốn tôi commit các thay đổi này không?"`).

---

## 🔗 QUY TẮC KHỞI TẠO SLUG & DỌN DẸP DATABASE (Chốt T08/2026)
- **Slug sạch nguyên bản:** Slug khi khởi tạo/cập nhật BẮT BUỘC là chuỗi sạch `Str::slug($name)`. TUYỆT ĐỐI KHÔNG cưỡng ép nối đuôi ID hoặc `-0` (`$slug = $baseSlug . '-' . $id`).
- **Kiểm tra Duy nhất Toàn hệ thống (Global Cross-Tables):** Đảm bảo tính duy nhất qua cả 5 bảng nội dung (`tp_pages`, `tp_products`, `tp_news`, `tp_cate_products`, `tp_cate_news`). CHỈ KHI phát hiện trùng tên với bản ghi khác mới nối thêm hậu tố số tăng dần (`-1`, `-2`, `-3`...).
- **Lệnh dọn dẹp:** Dùng lệnh `php artisan slug:clean` để tự động quét & đưa các slug dính đuôi thừa trong CSDL về bản chuẩn SEO nguyên bản.
