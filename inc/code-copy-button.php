<?php
/**
 * 代码一键复制按钮功能
 * Code Copy Button
 * 
 * @package MOE
 * @since 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * 添加代码复制按钮样式
 */
function moe_code_copy_button_styles() {
    ?>
    <style>
    /* 代码块容器 */
    .entry pre,
    .post pre,
    article pre {
        position: relative;
        padding-top: 45px !important;
    }
    
    /* 复制按钮 */
    .code-copy-btn {
        position: absolute;
        top: 8px;
        right: 8px;
        padding: 6px 12px;
        background: #24a5db;
        color: #fff;
        border: none;
        border-radius: 4px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        z-index: 10;
        display: flex;
        align-items: center;
        gap: 5px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }
    
    .code-copy-btn:hover {
        background: #1a8fbd;
        transform: translateY(-1px);
        box-shadow: 0 3px 8px rgba(0, 0, 0, 0.15);
    }
    
    .code-copy-btn:active {
        transform: translateY(0);
    }
    
    .code-copy-btn i {
        font-size: 13px;
    }
    
    /* 复制成功状态 */
    .code-copy-btn.copied {
        background: #4CAF50;
    }
    
    .code-copy-btn.copied::before {
        content: '✓';
        margin-right: 3px;
    }
    
    /* 代码语言标签 */
    .code-lang-label {
        position: absolute;
        top: 8px;
        left: 8px;
        padding: 6px 12px;
        background: rgba(0, 0, 0, 0.6);
        color: #fff;
        border-radius: 4px;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        z-index: 9;
    }
    
    /* 移动端优化 */
    @media screen and (max-width: 768px) {
        .code-copy-btn {
            padding: 5px 10px;
            font-size: 11px;
            top: 5px;
            right: 5px;
        }
        
        .code-lang-label {
            font-size: 10px;
            padding: 4px 8px;
            top: 5px;
            left: 5px;
        }
        
        .entry pre,
        .post pre,
        article pre {
            padding-top: 40px !important;
        }
    }
    
    /* 动画效果 */
    @keyframes copySuccess {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.1); }
    }
    
    .code-copy-btn.copied {
        animation: copySuccess 0.3s ease;
    }
    
    /* 工具提示 */
    .code-copy-btn[data-tooltip]::after {
        content: attr(data-tooltip);
        position: absolute;
        bottom: 100%;
        right: 0;
        margin-bottom: 5px;
        padding: 5px 10px;
        background: rgba(0, 0, 0, 0.8);
        color: #fff;
        font-size: 11px;
        border-radius: 3px;
        white-space: nowrap;
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.3s ease;
    }
    
    .code-copy-btn[data-tooltip]:hover::after {
        opacity: 1;
    }
    </style>
    <?php
}
add_action( 'wp_head', 'moe_code_copy_button_styles' );

/**
 * 添加代码复制按钮 JavaScript
 */
function moe_code_copy_button_script() {
    ?>
    <script>
    (function() {
        'use strict';
        
        // 页面加载完成后初始化
        document.addEventListener('DOMContentLoaded', function() {
            // 查找所有代码块
            var codeBlocks = document.querySelectorAll('pre code, pre');
            
            codeBlocks.forEach(function(block) {
                // 避免重复添加按钮
                if (block.parentElement.querySelector('.code-copy-btn')) {
                    return;
                }
                
                // 获取实际的代码元素
                var codeElement = block.tagName === 'CODE' ? block : block.querySelector('code');
                var preElement = block.tagName === 'PRE' ? block : block.parentElement;
                
                if (!preElement || preElement.tagName !== 'PRE') {
                    return;
                }
                
                // 检测代码语言（如果有）
                var className = codeElement ? codeElement.className : '';
                var lang = '';
                
                if (className) {
                    var match = className.match(/language-(\w+)/);
                    if (match) {
                        lang = match[1];
                    }
                }
                
                // 创建语言标签（如果检测到语言）
                if (lang) {
                    var langLabel = document.createElement('span');
                    langLabel.className = 'code-lang-label';
                    langLabel.textContent = lang;
                    preElement.appendChild(langLabel);
                }
                
                // 创建复制按钮
                var button = document.createElement('button');
                button.className = 'code-copy-btn';
                button.setAttribute('data-tooltip', '<?php echo esc_js( __( '点击复制', 'moe' ) ); ?>');
                button.innerHTML = '<i class="fa fa-copy"></i><span><?php echo esc_js( __( '复制', 'moe' ) ); ?></span>';
                
                // 点击复制
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    // 获取代码文本
                    var code = codeElement ? codeElement.textContent : preElement.textContent;
                    
                    // 移除按钮文本（避免复制到按钮文本）
                    var buttonText = button.textContent;
                    code = code.replace(buttonText, '').trim();
                    
                    // 复制到剪贴板
                    copyToClipboard(code).then(function() {
                        // 复制成功
                        button.classList.add('copied');
                        button.innerHTML = '<i class="fa fa-check"></i><span><?php echo esc_js( __( '已复制', 'moe' ) ); ?></span>';
                        button.setAttribute('data-tooltip', '<?php echo esc_js( __( '复制成功！', 'moe' ) ); ?>');
                        
                        // 2秒后恢复
                        setTimeout(function() {
                            button.classList.remove('copied');
                            button.innerHTML = '<i class="fa fa-copy"></i><span><?php echo esc_js( __( '复制', 'moe' ) ); ?></span>';
                            button.setAttribute('data-tooltip', '<?php echo esc_js( __( '点击复制', 'moe' ) ); ?>');
                        }, 2000);
                    }).catch(function(err) {
                        // 复制失败
                        console.error('复制失败:', err);
                        button.innerHTML = '<i class="fa fa-times"></i><span><?php echo esc_js( __( '失败', 'moe' ) ); ?></span>';
                        button.setAttribute('data-tooltip', '<?php echo esc_js( __( '复制失败', 'moe' ) ); ?>');
                        
                        setTimeout(function() {
                            button.innerHTML = '<i class="fa fa-copy"></i><span><?php echo esc_js( __( '复制', 'moe' ) ); ?></span>';
                            button.setAttribute('data-tooltip', '<?php echo esc_js( __( '点击复制', 'moe' ) ); ?>');
                        }, 2000);
                    });
                });
                
                // 添加按钮到代码块
                preElement.appendChild(button);
            });
        });
        
        // 复制到剪贴板函数
        function copyToClipboard(text) {
            // 优先使用现代 Clipboard API
            if (navigator.clipboard && window.isSecureContext) {
                return navigator.clipboard.writeText(text);
            } else {
                // 降级方案
                return new Promise(function(resolve, reject) {
                    var textArea = document.createElement('textarea');
                    textArea.value = text;
                    textArea.style.position = 'fixed';
                    textArea.style.left = '-999999px';
                    textArea.style.top = '-999999px';
                    document.body.appendChild(textArea);
                    textArea.focus();
                    textArea.select();
                    
                    try {
                        var successful = document.execCommand('copy');
                        document.body.removeChild(textArea);
                        
                        if (successful) {
                            resolve();
                        } else {
                            reject(new Error('execCommand failed'));
                        }
                    } catch (err) {
                        document.body.removeChild(textArea);
                        reject(err);
                    }
                });
            }
        }
    })();
    </script>
    <?php
}
add_action( 'wp_footer', 'moe_code_copy_button_script' );

