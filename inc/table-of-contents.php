<?php
/**
 * 文章目录（TOC）导航功能
 * Table of Contents
 * 
 * @package MOE
 * @since 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * 生成文章目录
 */
function moe_generate_toc( $content ) {
    if ( ! is_single() ) {
        return $content;
    }
    
    // 检查是否有足够的标题
    $headings = array();
    preg_match_all( '/<h([2-3])([^>]*)>(.*?)<\/h[2-3]>/i', $content, $matches, PREG_SET_ORDER );
    
    if ( count( $matches ) < 3 ) {
        return $content; // 少于3个标题，不显示目录
    }
    
    // 生成目录HTML
    $toc_html = '<div class="table-of-contents">';
    $toc_html .= '<div class="toc-header">';
    $toc_html .= '<i class="fa fa-list-ul"></i>';
    $toc_html .= '<strong>' . __( '文章目录', 'moe' ) . '</strong>';
    $toc_html .= '<button class="toc-toggle" aria-label="' . esc_attr__( '展开/收起', 'moe' ) . '">';
    $toc_html .= '<i class="fa fa-chevron-up"></i>';
    $toc_html .= '</button>';
    $toc_html .= '</div>';
    $toc_html .= '<nav class="toc-nav">';
    $toc_html .= '<ul class="toc-list">';
    
    $index = 0;
    $current_level = 2;
    
    foreach ( $matches as $heading ) {
        $level = intval( $heading[1] );
        $attrs = $heading[2];
        $title = strip_tags( $heading[3] );
        
        // 生成唯一ID
        $heading_id = 'toc-heading-' . $index;
        
        // 如果原标题已有ID，使用原ID
        if ( preg_match( '/id=["\']([^"\']+)["\']/', $attrs, $id_match ) ) {
            $heading_id = $id_match[1];
        }
        
        // 在内容中添加ID
        if ( strpos( $attrs, 'id=' ) === false ) {
            $content = str_replace(
                $heading[0],
                '<h' . $level . ' id="' . $heading_id . '"' . $attrs . '>' . $heading[3] . '</h' . $level . '>',
                $content
            );
        }
        
        // 处理多级列表
        if ( $level > $current_level ) {
            $toc_html .= '<ul class="toc-sublist">';
        } elseif ( $level < $current_level ) {
            $toc_html .= '</li></ul></li>';
        } elseif ( $index > 0 ) {
            $toc_html .= '</li>';
        }
        
        $toc_html .= '<li class="toc-item toc-level-' . $level . '">';
        $toc_html .= '<a href="#' . $heading_id . '" class="toc-link" data-level="' . $level . '">';
        $toc_html .= esc_html( $title );
        $toc_html .= '</a>';
        
        $current_level = $level;
        $index++;
    }
    
    // 关闭未闭合的标签
    if ( $current_level == 3 ) {
        $toc_html .= '</li></ul></li>';
    } else {
        $toc_html .= '</li>';
    }
    
    $toc_html .= '</ul>';
    $toc_html .= '</nav>';
    $toc_html .= '</div>';
    
    // 将目录插入到第一个段落之后
    $content = preg_replace( '/(<p[^>]*>.*?<\/p>)/', '$1' . $toc_html, $content, 1 );
    
    return $content;
}
add_filter( 'the_content', 'moe_generate_toc', 10 );

/**
 * 添加TOC样式和脚本
 */
function moe_toc_assets() {
    if ( ! is_single() ) {
        return;
    }
    ?>
    <style>
    /* 文章目录样式 */
    .table-of-contents {
        margin: 30px 0;
        padding: 0;
        background: linear-gradient(135deg, #f5f7fa 0%, #e8f4f8 100%);
        border-left: 4px solid #24a5db;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        overflow: hidden;
    }
    
    .toc-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 15px 20px;
        background: rgba(36, 165, 219, 0.1);
        border-bottom: 1px solid rgba(36, 165, 219, 0.2);
    }
    
    .toc-header strong {
        font-size: 16px;
        color: #333;
        margin-left: 8px;
    }
    
    .toc-header i.fa-list-ul {
        color: #24a5db;
        font-size: 18px;
    }
    
    .toc-toggle {
        background: none;
        border: none;
        color: #666;
        font-size: 16px;
        cursor: pointer;
        padding: 5px 10px;
        transition: all 0.3s ease;
    }
    
    .toc-toggle:hover {
        color: #24a5db;
        transform: scale(1.1);
    }
    
    .toc-toggle i {
        transition: transform 0.3s ease;
    }
    
    .table-of-contents.collapsed .toc-toggle i {
        transform: rotate(180deg);
    }
    
    .toc-nav {
        padding: 15px 20px;
        max-height: 400px;
        overflow-y: auto;
        transition: all 0.3s ease;
    }
    
    .table-of-contents.collapsed .toc-nav {
        max-height: 0;
        padding: 0 20px;
        overflow: hidden;
    }
    
    .toc-list,
    .toc-sublist {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    
    .toc-sublist {
        margin-left: 20px;
        margin-top: 5px;
    }
    
    .toc-item {
        margin: 5px 0;
    }
    
    .toc-link {
        display: block;
        padding: 8px 12px;
        color: #666;
        text-decoration: none;
        border-radius: 4px;
        transition: all 0.3s ease;
        font-size: 14px;
        line-height: 1.6;
        position: relative;
    }
    
    .toc-link::before {
        content: '';
        position: absolute;
        left: 0;
        top: 50%;
        transform: translateY(-50%);
        width: 0;
        height: 2px;
        background: #24a5db;
        transition: width 0.3s ease;
    }
    
    .toc-link:hover {
        background: rgba(36, 165, 219, 0.1);
        color: #24a5db;
        padding-left: 20px;
    }
    
    .toc-link:hover::before {
        width: 8px;
    }
    
    .toc-link.active {
        background: rgba(36, 165, 219, 0.15);
        color: #24a5db;
        font-weight: 600;
        padding-left: 20px;
    }
    
    .toc-link.active::before {
        width: 8px;
        background: #FF6B9D;
    }
    
    .toc-level-3 .toc-link {
        font-size: 13px;
        color: #999;
    }
    
    /* 滚动条样式 */
    .toc-nav::-webkit-scrollbar {
        width: 6px;
    }
    
    .toc-nav::-webkit-scrollbar-track {
        background: rgba(0, 0, 0, 0.05);
        border-radius: 3px;
    }
    
    .toc-nav::-webkit-scrollbar-thumb {
        background: rgba(36, 165, 219, 0.5);
        border-radius: 3px;
    }
    
    .toc-nav::-webkit-scrollbar-thumb:hover {
        background: rgba(36, 165, 219, 0.7);
    }
    
    /* 移动端优化 */
    @media screen and (max-width: 768px) {
        .table-of-contents {
            margin: 20px 0;
        }
        
        .toc-header {
            padding: 12px 15px;
        }
        
        .toc-header strong {
            font-size: 15px;
        }
        
        .toc-nav {
            padding: 12px 15px;
            max-height: 300px;
        }
        
        .toc-link {
            font-size: 13px;
            padding: 6px 10px;
        }
        
        .toc-level-3 .toc-link {
            font-size: 12px;
        }
    }
    
    /* 打印样式 */
    @media print {
        .table-of-contents {
            background: white;
            border: 1px solid #ddd;
        }
        
        .toc-toggle {
            display: none;
        }
        
        .toc-nav {
            max-height: none !important;
            overflow: visible !important;
        }
    }
    </style>
    
    <script>
    (function() {
        'use strict';
        
        document.addEventListener('DOMContentLoaded', function() {
            var toc = document.querySelector('.table-of-contents');
            if (!toc) return;
            
            // 折叠/展开功能
            var toggleBtn = toc.querySelector('.toc-toggle');
            if (toggleBtn) {
                toggleBtn.addEventListener('click', function() {
                    toc.classList.toggle('collapsed');
                });
            }
            
            // 平滑滚动到锚点
            var tocLinks = toc.querySelectorAll('.toc-link');
            tocLinks.forEach(function(link) {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    var targetId = this.getAttribute('href').slice(1);
                    var targetElement = document.getElementById(targetId);
                    
                    if (targetElement) {
                        var offsetTop = targetElement.offsetTop - 100;
                        window.scrollTo({
                            top: offsetTop,
                            behavior: 'smooth'
                        });
                        
                        // 更新active状态
                        updateActiveLink(this);
                    }
                });
            });
            
            // 滚动监听，高亮当前标题
            var headings = [];
            tocLinks.forEach(function(link) {
                var targetId = link.getAttribute('href').slice(1);
                var heading = document.getElementById(targetId);
                if (heading) {
                    headings.push({
                        element: heading,
                        link: link,
                        top: heading.offsetTop
                    });
                }
            });
            
            var ticking = false;
            
            window.addEventListener('scroll', function() {
                if (!ticking) {
                    window.requestAnimationFrame(function() {
                        highlightCurrentHeading();
                        ticking = false;
                    });
                    ticking = true;
                }
            });
            
            function highlightCurrentHeading() {
                var scrollTop = window.pageYOffset || document.documentElement.scrollTop;
                var currentHeading = null;
                
                for (var i = headings.length - 1; i >= 0; i--) {
                    if (scrollTop >= headings[i].top - 120) {
                        currentHeading = headings[i];
                        break;
                    }
                }
                
                if (currentHeading) {
                    updateActiveLink(currentHeading.link);
                }
            }
            
            function updateActiveLink(activeLink) {
                tocLinks.forEach(function(link) {
                    link.classList.remove('active');
                });
                activeLink.classList.add('active');
            }
            
            // 初始化高亮
            highlightCurrentHeading();
        });
    })();
    </script>
    <?php
}
add_action( 'wp_footer', 'moe_toc_assets' );

