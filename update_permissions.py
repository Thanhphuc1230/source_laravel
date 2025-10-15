#!/usr/bin/env python3
"""
Script để cập nhật permission checks cho các admin views
"""

import os
import re

# Mapping modules với permissions
module_permissions = {
    'cate_new': 'cate_news',  # Lưu ý: cate_new file nhưng permission là cate_news
    'slider': 'slider',
    'menu': 'menu', 
    'feedback': 'feedback',
    'contact': 'contact',
    'comment': 'comment',
    'analytics': 'analytics'
}

def update_view_permissions(module_folder, permission_name):
    """Cập nhật permission checks cho một module"""
    list_file = f"d:/laragon/www/source_laravel_10/resources/views/admin/modules/{module_folder}/list.blade.php"
    
    if not os.path.exists(list_file):
        print(f"File không tồn tại: {list_file}")
        return
    
    with open(list_file, 'r', encoding='utf-8') as f:
        content = f.read()
    
    # Pattern để thay thế "Thêm" button
    add_pattern = r'(<div class="col-sm-auto">\s*<div>\s*<a type="button" href="\{\{ route\(\'admin\.\' \. \$nameClass \. \'\.create\'\) \}}"[^>]*class="btn btn-success add-btn"[^>]*><i[^>]*class="ri-add-line[^"]*"[^>]*></i> Thêm </a>\s*</div>\s*</div>)'
    add_replacement = f'''@hasPermission('{permission_name}.create')
                                    <div class="col-sm-auto">
                                        <div>
                                            <a type="button" href="{{{{ route('admin.' . $nameClass . '.create') }}}}"
                                                class="btn btn-success add-btn"><i
                                                    class="ri-add-line align-bottom me-1"></i> Thêm </a>
                                        </div>
                                    </div>
                                    @endhasPermission'''
    
    # Pattern để thay thế "Xóa hết" button  
    delete_all_pattern = r'(<div class="col-sm-auto">\s*<a id="deleteSelectedItems" class="btn btn-danger add-btn">\s*<i class="ri-delete-bin-5-line"></i> Xóa hết\s*</a>\s*</div>)'
    delete_all_replacement = f'''@hasPermission('{permission_name}.delete')
                                    <div class="col-sm-auto">
                                        <a id="deleteSelectedItems" class="btn btn-danger add-btn">
                                            <i class="ri-delete-bin-5-line"></i> Xóa hết
                                        </a>
                                    </div>
                                    @endhasPermission'''
    
    # Thay thế buttons
    content = re.sub(add_pattern, add_replacement, content, flags=re.MULTILINE | re.DOTALL)
    content = re.sub(delete_all_pattern, delete_all_replacement, content, flags=re.MULTILINE | re.DOTALL)
    
    # Pattern cho edit button trong table rows
    edit_pattern = r'(<div class="edit">\s*<a href="\{\{ route\(\'admin\.\' \. \$nameClass \. \'\.edit\'[^}]*\}\}" class="btn btn-sm btn-success edit-item-btn">Sửa</a>\s*</div>)'
    edit_replacement = f'''@hasPermission('{permission_name}.edit')
                                                                    <div class="edit">
                                                                        <a href="{{{{ route('admin.' . $nameClass . '.edit', ['uuid' => $item->uuid, 'page' => $list->currentPage()]) }}}}"
                                                                            class="btn btn-sm btn-success edit-item-btn">Sửa</a>
                                                                    </div>
                                                                    @endhasPermission'''
    
    # Pattern cho delete button trong table rows
    delete_pattern = r'(<div class="remove">\s*<a href="\{\{ route\(\'admin\.\' \. \$nameClass \. \'\.destroy\'[^}]*\}\}"[^>]*class="btn btn-sm btn-danger remove-item-btn"[^>]*onclick="[^"]*">Xóa</a>\s*</div>)'
    delete_replacement = f'''@hasPermission('{permission_name}.delete')
                                                                    <div class="remove">
                                                                        <a href="{{{{ route('admin.' . $nameClass . '.destroy', ['uuid' => $item->uuid]) }}}}"
                                                                            class="btn btn-sm btn-danger remove-item-btn"
                                                                            onclick="return confirm('Xác nhận xóa {{{{ $nameItem }}}} ?')">Xóa</a>
                                                                    </div>
                                                                    @endhasPermission'''
    
    content = re.sub(edit_pattern, edit_replacement, content, flags=re.MULTILINE | re.DOTALL)
    content = re.sub(delete_pattern, delete_replacement, content, flags=re.MULTILINE | re.DOTALL)
    
    # Ghi lại file
    with open(list_file, 'w', encoding='utf-8') as f:
        f.write(content)
    
    print(f"Đã cập nhật permissions cho {module_folder}")

# Chạy cho tất cả modules
for module, permission in module_permissions.items():
    update_view_permissions(module, permission)

print("Hoàn thành cập nhật permissions cho tất cả modules!")