<?php
/**
 * 图片灯箱功能
 * Image Lightbox
 * 
 * @package MOE
 * @since 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * 添加图片灯箱样式和脚本
 */
function moe_image_lightbox() {
    if ( ! is_single() && ! is_page() ) {
        return;
    }
    ?>
    <style>
    /* 图片灯箱样式 */
    .lightbox-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.95);
        z-index: 99999;
        display: none;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .lightbox-overlay.active {
        display: flex;
        opacity: 1;
    }
    
    .lightbox-container {
        position: relative;
        max-width: 90%;
        max-height: 90%;
        transform: scale(0.9);
        transition: transform 0.3s ease;
    }
    
    .lightbox-overlay.active .lightbox-container {
        transform: scale(1);
    }
    
    .lightbox-image {
        max-width: 100%;
        max-height: 90vh;
        width: auto;
        height: auto;
        display: block;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.5);
        border-radius: 4px;
    }
    
    .lightbox-close {
        position: absolute;
        top: -50px;
        right: 0;
        width: 40px;
        height: 40px;
        background: rgba(255, 255, 255, 0.1);
        border: 2px solid rgba(255, 255, 255, 0.3);
        border-radius: 50%;
        color: #fff;
        font-size: 24px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }
    
    .lightbox-close:hover {
        background: rgba(255, 255, 255, 0.2);
        border-color: rgba(255, 255, 255, 0.5);
        transform: rotate(90deg);
    }
    
    .lightbox-prev,
    .lightbox-next {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        width: 50px;
        height: 50px;
        background: rgba(255, 255, 255, 0.1);
        border: 2px solid rgba(255, 255, 255, 0.3);
        border-radius: 50%;
        color: #fff;
        font-size: 24px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        z-index: 10;
    }
    
    .lightbox-prev {
        left: -70px;
    }
    
    .lightbox-next {
        right: -70px;
    }
    
    .lightbox-prev:hover,
    .lightbox-next:hover {
        background: rgba(255, 255, 255, 0.2);
        border-color: rgba(255, 255, 255, 0.5);
        transform: translateY(-50%) scale(1.1);
    }
    
    .lightbox-prev.disabled,
    .lightbox-next.disabled {
        opacity: 0.3;
        cursor: not-allowed;
    }
    
    .lightbox-caption {
        position: absolute;
        bottom: -60px;
        left: 0;
        right: 0;
        text-align: center;
        color: #fff;
        font-size: 14px;
        line-height: 1.6;
        padding: 10px 20px;
        background: rgba(0, 0, 0, 0.5);
        border-radius: 4px;
    }
    
    .lightbox-counter {
        position: absolute;
        top: -50px;
        left: 0;
        color: #fff;
        font-size: 14px;
        padding: 8px 16px;
        background: rgba(0, 0, 0, 0.5);
        border-radius: 20px;
    }
    
    .lightbox-loading {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        color: #fff;
        font-size: 48px;
        animation: spin 1s linear infinite;
    }
    
    @keyframes spin {
        from { transform: translate(-50%, -50%) rotate(0deg); }
        to { transform: translate(-50%, -50%) rotate(360deg); }
    }
    
    /* 可缩放的图片样式 */
    .entry img,
    .post img {
        cursor: zoom-in;
        transition: opacity 0.3s ease;
    }
    
    .entry img:hover,
    .post img:hover {
        opacity: 0.9;
    }
    
    /* 移动端优化 */
    @media screen and (max-width: 768px) {
        .lightbox-container {
            max-width: 95%;
            max-height: 95%;
        }
        
        .lightbox-close {
            top: 10px;
            right: 10px;
            width: 36px;
            height: 36px;
            font-size: 20px;
        }
        
        .lightbox-prev,
        .lightbox-next {
            width: 40px;
            height: 40px;
            font-size: 20px;
        }
        
        .lightbox-prev {
            left: 10px;
        }
        
        .lightbox-next {
            right: 10px;
        }
        
        .lightbox-caption {
            bottom: 10px;
            font-size: 13px;
        }
        
        .lightbox-counter {
            top: 10px;
            font-size: 12px;
        }
    }
    </style>
    
    <script>
    (function() {
        'use strict';
        
        // 创建灯箱HTML
        var lightboxHTML = '<div class="lightbox-overlay" id="lightbox">' +
            '<div class="lightbox-container">' +
            '<button class="lightbox-close" aria-label="<?php echo esc_js( __( '关闭', 'moe' ) ); ?>">' +
            '<i class="fa fa-times"></i>' +
            '</button>' +
            '<button class="lightbox-prev" aria-label="<?php echo esc_js( __( '上一张', 'moe' ) ); ?>">' +
            '<i class="fa fa-chevron-left"></i>' +
            '</button>' +
            '<img class="lightbox-image" src="" alt="">' +
            '<button class="lightbox-next" aria-label="<?php echo esc_js( __( '下一张', 'moe' ) ); ?>">' +
            '<i class="fa fa-chevron-right"></i>' +
            '</button>' +
            '<div class="lightbox-caption"></div>' +
            '<div class="lightbox-counter"></div>' +
            '<div class="lightbox-loading"><i class="fa fa-spinner fa-spin"></i></div>' +
            '</div>' +
            '</div>';
        
        // 页面加载完成后初始化
        document.addEventListener('DOMContentLoaded', function() {
            // 插入灯箱HTML
            document.body.insertAdjacentHTML('beforeend', lightboxHTML);
            
            var lightbox = document.getElementById('lightbox');
            var lightboxImage = lightbox.querySelector('.lightbox-image');
            var lightboxCaption = lightbox.querySelector('.lightbox-caption');
            var lightboxCounter = lightbox.querySelector('.lightbox-counter');
            var lightboxLoading = lightbox.querySelector('.lightbox-loading');
            var closeBtn = lightbox.querySelector('.lightbox-close');
            var prevBtn = lightbox.querySelector('.lightbox-prev');
            var nextBtn = lightbox.querySelector('.lightbox-next');
            
            var images = [];
            var currentIndex = 0;
            
            // 获取所有内容区域的图片
            var contentImages = document.querySelectorAll('.entry img, .post img');
            contentImages.forEach(function(img, index) {
                // 排除表情和小图标
                if (img.width > 100 && img.height > 100 && !img.classList.contains('emoji')) {
                    images.push({
                        src: img.src,
                        alt: img.alt || '',
                        title: img.title || ''
                    });
                    
                    // 添加点击事件
                    img.setAttribute('data-lightbox-index', images.length - 1);
                    img.addEventListener('click', function(e) {
                        e.preventDefault();
                        var index = parseInt(this.getAttribute('data-lightbox-index'));
                        openLightbox(index);
                    });
                }
            });
            
            // 打开灯箱
            function openLightbox(index) {
                currentIndex = index;
                showImage(currentIndex);
                lightbox.classList.add('active');
                document.body.style.overflow = 'hidden';
            }
            
            // 显示图片
            function showImage(index) {
                if (index < 0 || index >= images.length) return;
                
                lightboxLoading.style.display = 'block';
                lightboxImage.style.opacity = '0';
                
                var img = new Image();
                img.onload = function() {
                    lightboxImage.src = images[index].src;
                    lightboxImage.alt = images[index].alt;
                    
                    // 显示标题
                    if (images[index].title || images[index].alt) {
                        lightboxCaption.textContent = images[index].title || images[index].alt;
                        lightboxCaption.style.display = 'block';
                    } else {
                        lightboxCaption.style.display = 'none';
                    }
                    
                    // 显示计数
                    lightboxCounter.textContent = (index + 1) + ' / ' + images.length;
                    
                    // 更新按钮状态
                    prevBtn.classList.toggle('disabled', index === 0);
                    nextBtn.classList.toggle('disabled', index === images.length - 1);
                    
                    lightboxLoading.style.display = 'none';
                    lightboxImage.style.opacity = '1';
                };
                img.src = images[index].src;
            }
            
            // 关闭灯箱
            function closeLightbox() {
                lightbox.classList.remove('active');
                document.body.style.overflow = '';
            }
            
            // 上一张
            function prevImage() {
                if (currentIndex > 0) {
                    currentIndex--;
                    showImage(currentIndex);
                }
            }
            
            // 下一张
            function nextImage() {
                if (currentIndex < images.length - 1) {
                    currentIndex++;
                    showImage(currentIndex);
                }
            }
            
            // 绑定事件
            closeBtn.addEventListener('click', closeLightbox);
            prevBtn.addEventListener('click', prevImage);
            nextBtn.addEventListener('click', nextImage);
            
            // 点击背景关闭
            lightbox.addEventListener('click', function(e) {
                if (e.target === lightbox) {
                    closeLightbox();
                }
            });
            
            // 键盘导航
            document.addEventListener('keydown', function(e) {
                if (!lightbox.classList.contains('active')) return;
                
                switch(e.key) {
                    case 'Escape':
                        closeLightbox();
                        break;
                    case 'ArrowLeft':
                        prevImage();
                        break;
                    case 'ArrowRight':
                        nextImage();
                        break;
                }
            });
            
            // 触摸滑动支持
            var touchStartX = 0;
            var touchEndX = 0;
            
            lightbox.addEventListener('touchstart', function(e) {
                touchStartX = e.changedTouches[0].screenX;
            });
            
            lightbox.addEventListener('touchend', function(e) {
                touchEndX = e.changedTouches[0].screenX;
                handleSwipe();
            });
            
            function handleSwipe() {
                var diff = touchStartX - touchEndX;
                if (Math.abs(diff) > 50) {
                    if (diff > 0) {
                        nextImage(); // 向左滑动
                    } else {
                        prevImage(); // 向右滑动
                    }
                }
            }
        });
    })();
    </script>
    <?php
}
add_action( 'wp_footer', 'moe_image_lightbox' );

