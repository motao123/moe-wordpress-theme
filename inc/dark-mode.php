<?php
/**
 * 暗色模式功能
 * Dark Mode Toggle
 * 
 * @package MOE
 * @since 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * 添加暗色模式切换器
 */
function moe_dark_mode_toggle() {
    ?>
    <button id="dark-mode-toggle" class="dark-mode-toggle" aria-label="<?php esc_attr_e( '切换暗色模式', 'moe' ); ?>" title="<?php esc_attr_e( '切换暗色模式', 'moe' ); ?>">
        <i class="fa fa-moon-o dark-icon"></i>
        <i class="fa fa-sun-o light-icon"></i>
    </button>
    <?php
}
add_action( 'wp_footer', 'moe_dark_mode_toggle' );

/**
 * 添加暗色模式样式
 */
function moe_dark_mode_styles() {
    ?>
    <style>
    /* CSS变量定义 - 亮色模式（默认） */
    :root {
        /* 背景色 */
        --bg-primary: #ffffff;
        --bg-secondary: #f8f9fa;
        --bg-tertiary: #e9ecef;
        --bg-quaternary: #dee2e6;
        --bg-overlay: rgba(255, 255, 255, 0.95);
        
        /* 文字颜色 */
        --text-primary: #333333;
        --text-secondary: #666666;
        --text-tertiary: #999999;
        --text-quaternary: #cccccc;
        --text-inverse: #ffffff;
        
        /* 链接颜色 */
        --link-color: #24a5db;
        --link-hover: #1a8fbd;
        --link-visited: #6c63ff;
        
        /* 边框颜色 */
        --border-color: #e0e0e0;
        --border-light: #f0f0f0;
        --border-dark: #cccccc;
        
        /* 阴影 */
        --shadow-sm: rgba(0, 0, 0, 0.05);
        --shadow: rgba(0, 0, 0, 0.1);
        --shadow-md: rgba(0, 0, 0, 0.15);
        --shadow-lg: rgba(0, 0, 0, 0.2);
        
        /* 品牌颜色 */
        --color-primary: #FF6B9D;
        --color-secondary: #24a5db;
        --color-success: #4CAF50;
        --color-warning: #FFA726;
        --color-danger: #EF5350;
        --color-info: #29B6F6;
        
        /* 代码块 */
        --code-bg: #f5f5f5;
        --code-text: #c7254e;
        --code-border: #ddd;
        
        /* 卡片 */
        --card-bg: #ffffff;
        --card-border: #e0e0e0;
        --card-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        
        /* 输入框 */
        --input-bg: #ffffff;
        --input-border: #ddd;
        --input-focus: #24a5db;
        
        /* 过渡时间 */
        --transition-fast: 0.15s;
        --transition-normal: 0.3s;
        --transition-slow: 0.5s;
    }
    
    /* CSS变量定义 - 暗色模式 */
    [data-theme="dark"] {
        /* 背景色 */
        --bg-primary: #1a1a1a;
        --bg-secondary: #2d2d2d;
        --bg-tertiary: #3a3a3a;
        --bg-quaternary: #4a4a4a;
        --bg-overlay: rgba(26, 26, 26, 0.95);
        
        /* 文字颜色 */
        --text-primary: #e0e0e0;
        --text-secondary: #b0b0b0;
        --text-tertiary: #808080;
        --text-quaternary: #606060;
        --text-inverse: #1a1a1a;
        
        /* 链接颜色 */
        --link-color: #5fc3ff;
        --link-hover: #8ad4ff;
        --link-visited: #a78bfa;
        
        /* 边框颜色 */
        --border-color: #404040;
        --border-light: #353535;
        --border-dark: #505050;
        
        /* 阴影 */
        --shadow-sm: rgba(0, 0, 0, 0.2);
        --shadow: rgba(0, 0, 0, 0.3);
        --shadow-md: rgba(0, 0, 0, 0.4);
        --shadow-lg: rgba(0, 0, 0, 0.5);
        
        /* 品牌颜色（暗色模式下稍微调亮） */
        --color-primary: #FF8BB5;
        --color-secondary: #5fc3ff;
        --color-success: #66BB6A;
        --color-warning: #FFB74D;
        --color-danger: #FF6B6B;
        --color-info: #4FC3F7;
        
        /* 代码块 */
        --code-bg: #2d2d2d;
        --code-text: #ff6b9d;
        --code-border: #404040;
        
        /* 卡片 */
        --card-bg: #2d2d2d;
        --card-border: #404040;
        --card-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        
        /* 输入框 */
        --input-bg: #2d2d2d;
        --input-border: #404040;
        --input-focus: #5fc3ff;
    }
    
    /* ============================================
       基础元素应用变量
       ============================================ */
    
    * {
        transition: background-color var(--transition-normal) ease,
                    color var(--transition-normal) ease,
                    border-color var(--transition-normal) ease,
                    box-shadow var(--transition-normal) ease;
    }
    
    body {
        background-color: var(--bg-primary);
        color: var(--text-primary);
    }
    
    /* 容器元素 */
    #page,
    #content,
    .post,
    .widget,
    .comment-form,
    .sidebar {
        background-color: var(--bg-primary);
        color: var(--text-primary);
    }
    
    /* 标题元素 */
    .post-title,
    .widget-title,
    .archive-title,
    .search-title,
    .related-title,
    .share-title,
    h1, h2, h3, h4, h5, h6 {
        color: var(--text-primary);
    }
    
    /* 文本元素 */
    .post-meta,
    .entry,
    .comment-meta,
    .widget-content,
    p {
        color: var(--text-secondary);
    }
    
    /* 链接 */
    a {
        color: var(--link-color);
        transition: color var(--transition-fast) ease;
    }
    
    a:hover,
    a:focus {
        color: var(--link-hover);
    }
    
    a:visited {
        color: var(--link-visited);
    }
    
    /* 卡片和容器 */
    .post,
    .widget,
    .comment,
    .search-result,
    .article-card,
    .related-post-item,
    .related-posts {
        background-color: var(--card-bg);
        border-color: var(--card-border);
        box-shadow: var(--card-shadow);
    }
    
    /* 表单元素 */
    input[type="text"],
    input[type="email"],
    input[type="url"],
    input[type="search"],
    input[type="password"],
    textarea,
    select {
        background-color: var(--input-bg);
        color: var(--text-primary);
        border-color: var(--input-border);
    }
    
    input:focus,
    textarea:focus,
    select:focus {
        border-color: var(--input-focus);
        outline-color: var(--input-focus);
    }
    
    ::placeholder {
        color: var(--text-tertiary);
    }
    
    /* 代码块 */
    code,
    pre,
    .code-block,
    .entry pre code {
        background-color: var(--code-bg);
        color: var(--code-text);
        border-color: var(--code-border);
    }
    
    /* 引用块 */
    blockquote {
        background-color: var(--bg-secondary);
        border-left-color: var(--color-primary);
        color: var(--text-secondary);
    }
    
    /* 表格 */
    table {
        background-color: var(--card-bg);
        border-color: var(--border-color);
    }
    
    th {
        background-color: var(--bg-tertiary);
        color: var(--text-primary);
        border-color: var(--border-color);
    }
    
    td {
        background-color: var(--card-bg);
        color: var(--text-secondary);
        border-color: var(--border-color);
    }
    
    tr:hover td {
        background-color: var(--bg-secondary);
    }
    
    /* 分隔线 */
    hr {
        border-color: var(--border-color);
        opacity: 0.3;
    }
    
    /* 按钮 */
    button,
    .btn,
    .button,
    input[type="submit"],
    input[type="button"] {
        background-color: var(--color-primary);
        color: var(--text-inverse);
        border-color: var(--color-primary);
    }
    
    button:hover,
    .btn:hover,
    .button:hover {
        background-color: var(--color-secondary);
        border-color: var(--color-secondary);
    }
    
    /* 徽章和标签 */
    .badge,
    .tag,
    .label {
        background-color: var(--bg-tertiary);
        color: var(--text-primary);
    }
    
    /* 导航栏 */
    #nav-topbar,
    #nav-menu,
    .nav-wrap {
        background-color: var(--bg-primary);
        border-color: var(--border-color);
    }
    
    .nav-wrap a {
        color: var(--text-primary);
    }
    
    .nav-wrap a:hover {
        background-color: var(--bg-secondary);
        color: var(--link-hover);
    }
    
    /* 暗色模式切换按钮 */
    .dark-mode-toggle {
        position: fixed;
        right: 20px;
        top: 200px;
        width: 50px;
        height: 50px;
        background: var(--bg-secondary);
        border: 2px solid var(--border-color);
        border-radius: 50%;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        color: var(--text-primary);
        z-index: 9996;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px var(--shadow);
    }
    
    .dark-mode-toggle:hover {
        transform: translateX(-3px) scale(1.05);
        box-shadow: 0 6px 16px var(--shadow-hover);
    }
    
    .dark-mode-toggle .dark-icon {
        display: block;
    }
    
    .dark-mode-toggle .light-icon {
        display: none;
    }
    
    [data-theme="dark"] .dark-mode-toggle .dark-icon {
        display: none;
    }
    
    [data-theme="dark"] .dark-mode-toggle .light-icon {
        display: block;
    }
    
    /* ============================================
       暗色模式特殊处理
       ============================================ */
    
    /* 图片处理 */
    [data-theme="dark"] img:not(.emoji):not(.avatar) {
        opacity: 0.9;
        filter: brightness(0.95);
    }
    
    [data-theme="dark"] img:not(.emoji):not(.avatar):hover {
        opacity: 1;
        filter: brightness(1);
    }
    
    /* 头部和尾部 */
    [data-theme="dark"] #header {
        background-color: var(--bg-primary);
        border-bottom-color: var(--border-color);
        box-shadow: 0 2px 8px var(--shadow);
    }
    
    [data-theme="dark"] #footer {
        background-color: var(--bg-primary);
        border-top-color: var(--border-color);
        box-shadow: 0 -2px 8px var(--shadow-sm);
    }
    
    /* 搜索框 */
    [data-theme="dark"] .search-form input {
        background-color: var(--input-bg);
        color: var(--text-primary);
        border-color: var(--input-border);
    }
    
    /* 分页 */
    [data-theme="dark"] .pagination a,
    [data-theme="dark"] .pagination span {
        background-color: var(--bg-secondary);
        color: var(--text-primary);
        border-color: var(--border-color);
    }
    
    [data-theme="dark"] .pagination a:hover,
    [data-theme="dark"] .pagination .current {
        background-color: var(--color-primary);
        color: var(--text-inverse);
    }
    
    /* 高亮文本 */
    [data-theme="dark"] mark,
    [data-theme="dark"] .highlight {
        background: var(--color-warning);
        color: var(--text-inverse);
    }
    
    /* 版权声明 */
    [data-theme="dark"] .copyright-notice {
        background: linear-gradient(135deg, var(--bg-secondary) 0%, var(--bg-tertiary) 100%);
        border-left-color: var(--color-secondary);
    }
    
    /* 目录 */
    [data-theme="dark"] .table-of-contents {
        background: linear-gradient(135deg, var(--bg-secondary) 0%, var(--bg-tertiary) 100%);
        border-left-color: var(--color-secondary);
    }
    
    [data-theme="dark"] .toc-header {
        background: rgba(95, 195, 255, 0.1);
        border-bottom-color: rgba(95, 195, 255, 0.2);
    }
    
    [data-theme="dark"] .toc-list a {
        color: var(--text-secondary);
    }
    
    [data-theme="dark"] .toc-list a:hover {
        color: var(--link-hover);
    }
    
    /* 社交分享 */
    [data-theme="dark"] .social-share-wrapper {
        background: linear-gradient(135deg, #3a2f3f 0%, #4a3845 100%);
        border-color: #8B5A7C;
    }
    
    [data-theme="dark"] .share-btn {
        background: rgba(255, 255, 255, 0.1);
        color: var(--text-primary);
        border-color: var(--border-color);
    }
    
    [data-theme="dark"] .share-btn:hover {
        background: rgba(255, 255, 255, 0.2);
    }
    
    /* 相关推荐 */
    [data-theme="dark"] .related-posts {
        background-color: var(--card-bg);
        box-shadow: var(--card-shadow);
    }
    
    [data-theme="dark"] .related-post-item {
        background-color: var(--bg-tertiary);
        border-color: var(--border-color);
    }
    
    [data-theme="dark"] .related-post-item:hover {
        border-color: var(--color-primary);
        box-shadow: 0 8px 20px var(--shadow-md);
    }
    
    /* 评论区 */
    [data-theme="dark"] .comment {
        background-color: var(--bg-secondary);
        border-color: var(--border-color);
    }
    
    [data-theme="dark"] .comment-reply-link {
        color: var(--link-color);
    }
    
    [data-theme="dark"] .comment-author cite {
        color: var(--text-primary);
    }
    
    /* 侧边栏小部件 */
    [data-theme="dark"] .widget {
        background-color: var(--card-bg);
        border: 1px solid var(--card-border);
    }
    
    [data-theme="dark"] .widget ul li {
        border-bottom-color: var(--border-light);
    }
    
    /* 404页面 */
    [data-theme="dark"] .error-404 {
        background-color: var(--bg-secondary);
    }
    
    /* 归档页面 */
    [data-theme="dark"] .archive-header {
        background: linear-gradient(135deg, #3a2f3f 0%, #4a3845 100%);
        border-color: #8B5A7C;
        box-shadow: 0 2px 8px rgba(139, 90, 124, 0.3);
    }
    
    [data-theme="dark"] .archive-title {
        color: #FFB6C1;
    }
    
    [data-theme="dark"] .archive-title i {
        color: #FF9CB0;
    }
    
    [data-theme="dark"] .archive-description {
        color: #ccc;
    }
    
    [data-theme="dark"] .author-avatar img {
        border-color: #8B5A7C;
    }
    
    /* 搜索页面 */
    [data-theme="dark"] .search-header {
        background: linear-gradient(135deg, #3a2f3f 0%, #4a3845 100%);
        border-color: #8B5A7C;
        box-shadow: 0 2px 8px rgba(139, 90, 124, 0.3);
    }
    
    [data-theme="dark"] .search-title {
        color: #FFB6C1;
    }
    
    [data-theme="dark"] .search-query {
        color: #FFB6C1;
    }
    
    [data-theme="dark"] .search-results-count {
        color: #ccc;
    }
    
    /* 归档统计 */
    [data-theme="dark"] .archive-stats {
        background: linear-gradient(135deg, #3a2f3f 0%, #4a3845 100%);
        border-color: #8B5A7C;
        box-shadow: 0 2px 8px rgba(139, 90, 124, 0.3);
    }
    
    [data-theme="dark"] .archive-stats-title {
        color: #FFB6C1;
    }
    
    [data-theme="dark"] .stat-item {
        background: rgba(255, 255, 255, 0.05);
    }
    
    [data-theme="dark"] .stat-number {
        color: #FFB6C1;
    }
    
    [data-theme="dark"] .stat-label {
        color: #ccc;
    }
    
    [data-theme="dark"] .timeline-year,
    [data-theme="dark"] .timeline-month {
        border-left-color: var(--color-secondary);
    }
    
    /* 加载动画 */
    [data-theme="dark"] .loading,
    [data-theme="dark"] .spinner {
        border-color: var(--border-color);
        border-top-color: var(--color-primary);
    }
    
    /* 通知/提示 */
    [data-theme="dark"] .notice,
    [data-theme="dark"] .alert {
        background-color: var(--bg-secondary);
        border-color: var(--border-color);
        color: var(--text-primary);
    }
    
    /* 选中文本 */
    [data-theme="dark"] ::selection {
        background: var(--color-primary);
        color: var(--text-inverse);
    }
    
    [data-theme="dark"] ::-moz-selection {
        background: var(--color-primary);
        color: var(--text-inverse);
    }
    
    /* 滚动条 */
    [data-theme="dark"] ::-webkit-scrollbar {
        width: 12px;
        height: 12px;
        background-color: var(--bg-secondary);
    }
    
    [data-theme="dark"] ::-webkit-scrollbar-thumb {
        background-color: var(--bg-quaternary);
        border-radius: 6px;
    }
    
    [data-theme="dark"] ::-webkit-scrollbar-thumb:hover {
        background-color: var(--border-dark);
    }
    
    [data-theme="dark"] ::-webkit-scrollbar-track {
        background-color: var(--bg-tertiary);
    }
    
    /* 代码高亮微调 */
    [data-theme="dark"] .entry pre {
        background-color: var(--code-bg);
        border-color: var(--code-border);
    }
    
    [data-theme="dark"] .entry code {
        background-color: var(--code-bg);
        color: var(--code-text);
    }
    
    /* 文章列表卡片 */
    [data-theme="dark"] .article-card {
        background-color: var(--card-bg);
        border: 1px solid var(--card-border);
    }
    
    [data-theme="dark"] .article-card:hover {
        border-color: var(--color-primary);
        box-shadow: 0 8px 24px var(--shadow-md);
    }
    
    /* 标签云 */
    [data-theme="dark"] .tagcloud a,
    [data-theme="dark"] .tag-cloud-link {
        background-color: var(--bg-tertiary);
        color: var(--text-secondary);
        border-color: var(--border-color);
    }
    
    [data-theme="dark"] .tagcloud a:hover,
    [data-theme="dark"] .tag-cloud-link:hover {
        background-color: var(--color-primary);
        color: var(--text-inverse);
    }
    
    /* 面包屑导航 */
    [data-theme="dark"] .breadcrumbs {
        background-color: var(--bg-secondary);
        color: var(--text-secondary);
    }
    
    [data-theme="dark"] .breadcrumbs a {
        color: var(--link-color);
    }
    
    /* 返回顶部按钮 */
    [data-theme="dark"] #top {
        background-color: var(--color-primary);
        color: var(--text-inverse);
        box-shadow: 0 4px 15px var(--shadow-md);
    }
    
    /* 图片灯箱 */
    [data-theme="dark"] .lightbox {
        background-color: rgba(26, 26, 26, 0.95);
    }
    
    [data-theme="dark"] .lightbox img {
        opacity: 1;
        filter: none;
    }
    
    /* 移动端优化 */
    @media screen and (max-width: 768px) {
        .dark-mode-toggle {
            right: 10px;
            top: auto;
            bottom: 200px;
            width: 45px;
            height: 45px;
            font-size: 20px;
        }
    }
    
    @media screen and (max-width: 480px) {
        .dark-mode-toggle {
            bottom: 180px;
            width: 40px;
            height: 40px;
            font-size: 18px;
        }
    }
    
    /* 切换动画 */
    @keyframes darkModeIn {
        from {
            opacity: 0;
            transform: rotate(-180deg);
        }
        to {
            opacity: 1;
            transform: rotate(0);
        }
    }
    
    .dark-mode-toggle i {
        animation: darkModeIn 0.5s ease;
    }
    </style>
    
    <script>
    (function() {
        'use strict';
        
        var MOE_DARK_MODE = {
            // 配置
            config: {
                storageKey: 'moe_theme',
                themeAttribute: 'data-theme',
                transitionDuration: 300
            },
            
            // 从localStorage读取用户选择
            getSavedTheme: function() {
                try {
                    return localStorage.getItem(this.config.storageKey);
                } catch (e) {
                    console.warn('无法读取本地存储:', e);
                    return null;
                }
            },
            
            // 保存主题到localStorage
            saveTheme: function(theme) {
                try {
                    localStorage.setItem(this.config.storageKey, theme);
                } catch (e) {
                    console.warn('无法保存到本地存储:', e);
                }
            },
            
            // 检测系统主题偏好
            getSystemTheme: function() {
                if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
                    return 'dark';
                }
                return 'light';
            },
            
            // 获取当前主题
            getCurrentTheme: function() {
                return document.documentElement.getAttribute(this.config.themeAttribute) || 'light';
            },
            
            // 设置主题
            setTheme: function(theme, skipTransition) {
                if (skipTransition) {
                    document.documentElement.style.transition = 'none';
                }
                
                document.documentElement.setAttribute(this.config.themeAttribute, theme);
                this.saveTheme(theme);
                
                if (skipTransition) {
                    // 强制重排
                    document.documentElement.offsetHeight;
                    setTimeout(function() {
                        document.documentElement.style.transition = '';
                    }, 10);
                }
                
                // 触发自定义事件
                this.dispatchThemeChangeEvent(theme);
            },
            
            // 切换主题
            toggleTheme: function() {
                var currentTheme = this.getCurrentTheme();
                var newTheme = currentTheme === 'dark' ? 'light' : 'dark';
                this.setTheme(newTheme);
                return newTheme;
            },
            
            // 触发主题变化事件
            dispatchThemeChangeEvent: function(theme) {
                if (typeof CustomEvent !== 'undefined') {
                    var event = new CustomEvent('themechange', {
                        detail: { theme: theme }
                    });
                    window.dispatchEvent(event);
                }
            },
            
            // 初始化主题
            initTheme: function() {
                var savedTheme = this.getSavedTheme();
                var systemTheme = this.getSystemTheme();
                var initialTheme = savedTheme || systemTheme;
                
                // 立即应用主题，不需要过渡动画
                this.setTheme(initialTheme, true);
            },
            
            // 初始化切换按钮
            initToggleButton: function() {
                var self = this;
                var toggleBtn = document.getElementById('dark-mode-toggle');
                
                if (!toggleBtn) {
                    console.warn('未找到暗色模式切换按钮');
                    return;
                }
                
                // 点击切换
                toggleBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    var newTheme = self.toggleTheme();
                    
                    // 添加切换动画
                    this.style.animation = 'none';
                    setTimeout(function() {
                        toggleBtn.style.animation = '';
                    }, 10);
                    
                    // 显示切换提示（可选）
                    self.showThemeNotification(newTheme);
                });
                
                // 添加键盘快捷键支持 (Ctrl/Cmd + Shift + D)
                document.addEventListener('keydown', function(e) {
                    if ((e.ctrlKey || e.metaKey) && e.shiftKey && e.key === 'D') {
                        e.preventDefault();
                        toggleBtn.click();
                    }
                });
            },
            
            // 监听系统主题变化
            watchSystemTheme: function() {
                var self = this;
                
                if (!window.matchMedia) {
                    return;
                }
                
                var darkModeQuery = window.matchMedia('(prefers-color-scheme: dark)');
                
                // 使用现代API或备用方案
                var changeHandler = function(e) {
                    // 只在用户未手动设置时跟随系统
                    if (!self.getSavedTheme()) {
                        self.setTheme(e.matches ? 'dark' : 'light');
                    }
                };
                
                if (darkModeQuery.addEventListener) {
                    darkModeQuery.addEventListener('change', changeHandler);
                } else if (darkModeQuery.addListener) {
                    // 备用方案（旧浏览器）
                    darkModeQuery.addListener(changeHandler);
                }
            },
            
            // 显示主题切换通知（可选）
            showThemeNotification: function(theme) {
                // 可以在这里添加一个小的通知提示
                // 例如：显示 "已切换到暗色模式" 或 "已切换到亮色模式"
                console.log('主题已切换至:', theme === 'dark' ? '暗色模式' : '亮色模式');
            },
            
            // 初始化所有功能
            init: function() {
                var self = this;
                
                // 立即应用主题（在DOM加载之前）
                this.initTheme();
                
                // DOM加载完成后初始化其他功能
                if (document.readyState === 'loading') {
                    document.addEventListener('DOMContentLoaded', function() {
                        self.initToggleButton();
                        self.watchSystemTheme();
                    });
                } else {
                    // DOM已经加载完成
                    self.initToggleButton();
                    self.watchSystemTheme();
                }
            }
        };
        
        // 启动暗色模式功能
        MOE_DARK_MODE.init();
        
        // 将对象暴露到全局，供其他脚本使用
        window.MOE_DARK_MODE = MOE_DARK_MODE;
        
    })();
    </script>
    <?php
}
add_action( 'wp_head', 'moe_dark_mode_styles' );

