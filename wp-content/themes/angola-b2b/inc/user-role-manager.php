<?php
/**
 * User Role Management
 * 
 * Simplifies user roles and adds helpful descriptions
 */

// Remove unnecessary default WordPress roles
function angola_b2b_remove_unnecessary_roles() {
    // Get all roles
    $roles_to_remove = array(
        'subscriber',      // 订阅者
        'contributor',     // 贡献者（将在后面重新添加，确保干净）
        'author',          // 作者
        'editor',          // 编辑
        'translator'       // Translator (来自Polylang)
    );
    
    foreach ($roles_to_remove as $role_slug) {
        if (get_role($role_slug)) {
            remove_role($role_slug);
        }
    }
    
    // Re-add contributor role (贡献者) with specific capabilities
    if (!get_role('contributor')) {
        add_role(
            'contributor',
            '贡献者',
            array(
                'read'         => true,
                'edit_posts'   => true,
                'delete_posts' => false,
            )
        );
    }
}
add_action('init', 'angola_b2b_remove_unnecessary_roles');

// Filter the list of editable roles to only show what we want
function angola_b2b_filter_editable_roles($all_roles) {
    // Only keep these roles
    $allowed_roles = array(
        'administrator',           // 管理员 (必须保留)
        'cn_product_manager',      // 中国产品管理员
        'ao_product_editor',       // 安哥拉产品编辑
        'contributor',             // 贡献者
    );
    
    // Filter out all other roles
    foreach ($all_roles as $role_slug => $role_info) {
        if (!in_array($role_slug, $allowed_roles)) {
            unset($all_roles[$role_slug]);
        }
    }
    
    return $all_roles;
}
add_filter('editable_roles', 'angola_b2b_filter_editable_roles');

// Add role descriptions on user edit page
function angola_b2b_add_role_descriptions() {
    $screen = get_current_screen();
    
    // Only on user edit/new user pages
    if ($screen->id !== 'user-edit' && $screen->id !== 'user') {
        return;
    }
    ?>
    <style>
        .angola-role-descriptions {
            background: #f0f6fc;
            border-left: 4px solid #2271b1;
            padding: 15px 20px;
            margin: 15px 0;
            border-radius: 4px;
        }
        .angola-role-descriptions h4 {
            margin: 0 0 12px 0;
            font-size: 14px;
            color: #1d2327;
        }
        .angola-role-item {
            margin: 10px 0;
            padding: 10px;
            background: white;
            border-radius: 4px;
            border: 1px solid #dcdcde;
        }
        .angola-role-item strong {
            color: #2271b1;
            font-size: 13px;
        }
        .angola-role-item ul {
            margin: 8px 0 0 20px;
            font-size: 12px;
            color: #50575e;
        }
        .angola-role-item ul li {
            margin: 4px 0;
        }
        .angola-role-warning {
            background: #fcf0f1;
            border-left-color: #d63638;
            margin-top: 10px;
            padding: 10px 15px;
            font-size: 12px;
            color: #50575e;
        }
    </style>
    <script>
    jQuery(document).ready(function($) {
        // Find the role dropdown
        var $roleField = $('#role');
        
        if ($roleField.length) {
            // Create description box
            var descriptions = `
                <div class="angola-role-descriptions">
                    <h4>📋 角色权限说明</h4>
                    
                    <div class="angola-role-item">
                        <strong>🔧 管理员 (Administrator)</strong>
                        <ul>
                            <li>✅ 拥有所有权限</li>
                            <li>✅ 可以管理所有用户、插件、主题</li>
                            <li>✅ 可以修改网站设置和代码</li>
                            <li>⚠️ 最高权限，请谨慎分配</li>
                        </ul>
                    </div>
                    
                    <div class="angola-role-item">
                        <strong>🇨🇳 中国产品管理员 (CN Product Manager)</strong>
                        <ul>
                            <li>✅ 管理所有产品（添加、编辑、删除、发布）</li>
                            <li>✅ 管理产品分类和标签</li>
                            <li>✅ 管理新闻（添加、编辑、删除、发布）</li>
                            <li>✅ 管理新闻分类和标签</li>
                            <li>✅ 上传和管理媒体库文件</li>
                            <li>✅ 设置首页内容</li>
                            <li>✅ 查看和管理所有4种语言的内容</li>
                            <li>❌ 不能安装插件或修改主题</li>
                        </ul>
                    </div>
                    
                    <div class="angola-role-item">
                        <strong>🇦🇴 安哥拉产品编辑 (AO Product Editor)</strong>
                        <ul>
                            <li>✅ 添加新产品</li>
                            <li>✅ 编辑自己创建的产品</li>
                            <li>✅ 上传产品图片到媒体库</li>
                            <li>✅ 使用葡萄牙语界面</li>
                            <li>❌ 不能删除或发布产品（需要中国管理员审核）</li>
                            <li>❌ 不能管理产品分类和标签</li>
                            <li>❌ 不能管理新闻</li>
                            <li>💡 适合安哥拉本地员工录入产品信息</li>
                        </ul>
                    </div>
                    
                    <div class="angola-role-item">
                        <strong>📝 贡献者 (Contributor)</strong>
                        <ul>
                            <li>✅ 撰写和编辑自己的文章</li>
                            <li>✅ 阅读所有已发布内容</li>
                            <li>❌ 不能发布文章（需要审核）</li>
                            <li>❌ 不能上传文件或图片</li>
                            <li>💡 适合外部撰稿人或临时协作者</li>
                        </ul>
                    </div>
                    
                    <div class="angola-role-warning">
                        <strong>⚠️ 重要提示：</strong>
                        <br>• 一个用户只能有一个角色
                        <br>• 管理员角色应该只分配给完全可信任的人员
                        <br>• 建议使用"最小权限原则"：根据实际工作需要分配角色
                    </div>
                </div>
            `;
            
            // Insert after the role field
            $roleField.closest('tr').after('<tr><td colspan="2">' + descriptions + '</td></tr>');
        }
    });
    </script>
    <?php
}
add_action('admin_footer-user-edit.php', 'angola_b2b_add_role_descriptions');
add_action('admin_footer-user-new.php', 'angola_b2b_add_role_descriptions');

// Customize role display names
function angola_b2b_translate_role_names($translated_text, $text, $domain) {
    // Get user locale
    $user_locale = get_user_locale();
    
    // Role translations
    $role_translations = array(
        'zh_CN' => array(
            'Administrator' => '管理员',
            'Contributor' => '贡献者',
        ),
        'pt_PT' => array(
            'Administrator' => 'Administrador',
            'Contributor' => 'Colaborador',
        ),
    );
    
    // Apply translations based on locale
    if ($user_locale === 'pt_PT' && isset($role_translations['pt_PT'][$text])) {
        return $role_translations['pt_PT'][$text];
    } elseif ($user_locale === 'zh_CN' && isset($role_translations['zh_CN'][$text])) {
        return $role_translations['zh_CN'][$text];
    }
    
    return $translated_text;
}
add_filter('gettext', 'angola_b2b_translate_role_names', 20, 3);

/**
 * 降低密码强度要求
 * 允许：字母、数字、符号 3种搭配即可
 */
function angola_b2b_lower_password_strength_requirement() {
    ?>
    <script type="text/javascript">
    jQuery(document).ready(function($) {
        // 修改WordPress密码强度检查
        if (typeof wp !== 'undefined' && typeof wp.passwordStrength !== 'undefined') {
            // 保存原始函数
            var originalPasswordStrength = wp.passwordStrength.meter;
            
            // 覆盖密码强度检测函数
            wp.passwordStrength.meter = function(password, blacklist, minLength) {
                // 如果密码为空，返回空
                if (password.length === 0) {
                    return -1;
                }
                
                // 检查长度（至少8位）
                if (password.length < 8) {
                    return 2; // too short (弱)
                }
                
                // 检查是否包含字母、数字、符号中的至少3种
                var hasLetter = /[a-zA-Z]/.test(password);
                var hasNumber = /[0-9]/.test(password);
                var hasSymbol = /[^a-zA-Z0-9]/.test(password);
                
                var typeCount = (hasLetter ? 1 : 0) + (hasNumber ? 1 : 0) + (hasSymbol ? 1 : 0);
                
                if (typeCount >= 2) {
                    return 4; // strong (强)
                } else {
                    return 3; // medium (中等)
                }
            };
        }
        
        // 修改密码强度文本提示
        $(document).on('DOMContentLoaded', function() {
            setTimeout(function() {
                $('#pass-strength-result').removeClass('short bad good strong');
            }, 100);
        });
    });
    </script>
    <?php
}
add_action('admin_footer-user-new.php', 'angola_b2b_lower_password_strength_requirement');
add_action('admin_footer-profile.php', 'angola_b2b_lower_password_strength_requirement');
add_action('admin_footer-user-edit.php', 'angola_b2b_lower_password_strength_requirement');

