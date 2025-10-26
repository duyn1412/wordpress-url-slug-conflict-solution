# Xử Lý Xung Đột URL Cho CPT và Taxonomy Có Cùng Slug

## Vấn Đề
Khi custom post type và custom taxonomy có cùng slug, WordPress sẽ chỉ nhận diện được một trong hai và cái còn lại sẽ trả về 404.

## Giải Pháp
Code trong `functions.php` sẽ tự động xử lý xung đột này bằng cách:

1. **Hook vào parse_query**: Can thiệp vào quá trình xử lý query của WordPress
2. **Phân biệt URL**: Dựa trên segment thứ 2 để xác định là post hay taxonomy
3. **Xử lý query**: Set đúng query variables để WordPress hiển thị đúng content

## Cách Hoạt Động

### URL Structure
- `/campaign/` → Archive page (tất cả campaigns)
- `/campaign/post-name/` → Single campaign post
- `/campaign/category-name/` → Campaign category archive

### Logic Phân Biệt
1. **Kiểm tra post trước**: Tìm xem có post nào có slug = `post-name` không
2. **Nếu tìm thấy post**: Set query để hiển thị single post
3. **Nếu không tìm thấy post**: Tìm taxonomy term có slug = `category-name`
4. **Nếu tìm thấy term**: Set query để hiển thị taxonomy archive
5. **Nếu không tìm thấy gì**: Trả về 404

## Yêu Cầu
- CPT: `ta_campaign` (slug: `campaign`)
- Taxonomy: `ta-campaign-category` (slug: `campaign`)

## Lưu Ý
- Code sẽ tự động chạy khi có xung đột slug
- Không cần cấu hình thêm
- Tương thích với tất cả themes
- Hoạt động với CPT và taxonomy được đăng ký bằng ACF
