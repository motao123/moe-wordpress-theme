<?php
/**
 * 文章字体大小调节功能
 * Font Size Control
 * 
 * @package MOE
 * @since 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * 添加字体大小调节控制面板
 */
function moe_font_size_control() {
    if ( ! is_single() ) {
        return;
    }
    ?>
    <div class="font-size-control">
        <button class="font-size-btn font-decrease" data-action="decrease" title="<?php esc_attr_e( '减小字体', 'moe' ); ?>">
            <i class="fa fa-font"></i> A-
        </button>
        <button class="font-size-btn font-reset" data-action="reset" title="<?php esc_attr_e( '默认字体', 'moe' ); ?>">
            <i class="fa fa-font"></i> A
        </button>
        <button class="font-size-btn font-increase" data-action="increase" title="<?php esc_attr_e( '增大字体', 'moe' ); ?>">
            <i class="fa fa-font"></i> A+
        </button>
    </div>
    
    <style>
    /* 字体大小调节控制面板 */
    .font-size-control {
        position: fixed;
        right: 20px;
        top: 150px;
        display: flex;
        flex-direction: column;
        gap: 5px;
        z-index: 9997;
        background: #fff;
        padding: 10px;
        border-radius: 8px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }
    
    .font-size-btn {
        width: 50px;
        height: 45px;
        background: #f8f9fa;
        border: 2px solid #e0e0e0;
        border-radius: 6px;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        font-weight: bold;
        color: #666;
    }
    
    .font-size-btn i {
        font-size: 14px;
        margin-bottom: 2px;
    }
    
    .font-size-btn:hover {
        background: #24a5db;
        border-color: #24a5db;
        color: #fff;
        transform: translateX(-3px);
    }
    
    .font-size-btn.active {
        background: #FF6B9D;
        border-color: #FF6B9D;
        color: #fff;
    }
    
    /* 字体大小等级 */
    .entry.font-small {
        font-size: 14px !important;
        line-height: 1.8 !important;
    }
    
    .entry.font-medium {
        font-size: 16px !important;
        line-height: 1.9 !important;
    }
    
    .entry.font-large {
        font-size: 18px !important;
        line-height: 2.0 !important;
    }
    
    .entry.font-xlarge {
        font-size: 20px !important;
        line-height: 2.1 !important;
    }
    
    .entry.font-xxlarge {
        font-size: 22px !important;
        line-height: 2.2 !important;
    }
    
    /* 移动端优化 */
    @media screen and (max-width: 768px) {
        .font-size-control {
            right: 10px;
            top: auto;
            bottom: 150px;
            padding: 8px;
            flex-direction: row;
        }
        
        .font-size-btn {
            width: 40px;
            height: 40px;
            font-size: 11px;
        }
        
        .font-size-btn i {
            font-size: 12px;
        }
    }
    
    @media screen and (max-width: 480px) {
        .font-size-control {
            bottom: 130px;
        }
        
        .font-size-btn {
            width: 35px;
            height: 35px;
            font-size: 10px;
        }
    }
    </style>
    
    <script>
    (function() {
        'use strict';
        
        // 默认字体大小设置
        var fontSizes = ['font-small', 'font-medium', 'font-large', 'font-xlarge', 'font-xxlarge'];
        var defaultSize = 'font-medium'; // 默认中等
        var currentSizeIndex = 1; // 默认索引（medium）
        
        // 从 localStorage 读取用户选择
        var savedSize = localStorage.getItem('moe_font_size');
        if (savedSize && fontSizes.indexOf(savedSize) !== -1) {
            currentSizeIndex = fontSizes.indexOf(savedSize);
            applyFontSize(savedSize);
        } else {
            applyFontSize(defaultSize);
        }
        
        // 绑定按钮事件
        document.querySelectorAll('.font-size-btn').forEach(function(btn) {
            btn.addEventListener('click', function() {
                var action = this.getAttribute('data-action');
                
                switch(action) {
                    case 'decrease':
                        if (currentSizeIndex > 0) {
                            currentSizeIndex--;
                            applyFontSize(fontSizes[currentSizeIndex]);
                        }
                        break;
                    
                    case 'increase':
                        if (currentSizeIndex < fontSizes.length - 1) {
                            currentSizeIndex++;
                            applyFontSize(fontSizes[currentSizeIndex]);
                        }
                        break;
                    
                    case 'reset':
                        currentSizeIndex = 1; // 重置为 medium
                        applyFontSize(defaultSize);
                        break;
                }
                
                // 保存到 localStorage
                localStorage.setItem('moe_font_size', fontSizes[currentSizeIndex]);
                
                // 更新按钮状态
                updateButtonState(action);
            });
        });
        
        // 应用字体大小
        function applyFontSize(size) {
            var entry = document.querySelector('.entry');
            if (!entry) return;
            
            // 移除所有字体大小类
            fontSizes.forEach(function(s) {
                entry.classList.remove(s);
            });
            
            // 添加新的字体大小类
            entry.classList.add(size);
        }
        
        // 更新按钮状态
        function updateButtonState(action) {
            // 移除所有 active 状态
            document.querySelectorAll('.font-size-btn').forEach(function(btn) {
                btn.classList.remove('active');
            });
            
            // 添加当前按钮的 active 状态
            var activeBtn = null;
            if (currentSizeIndex === 0) {
                activeBtn = document.querySelector('.font-decrease');
            } else if (currentSizeIndex === fontSizes.length - 1) {
                activeBtn = document.querySelector('.font-increase');
            } else if (currentSizeIndex === 1) {
                activeBtn = document.querySelector('.font-reset');
            }
            
            if (activeBtn) {
                activeBtn.classList.add('active');
            }
        }
        
        // 初始化按钮状态
        updateButtonState();
    })();
    </script>
    <?php
}
add_action( 'wp_footer', 'moe_font_size_control' );

