/**
 * MOE WordPress Theme Scripts
 * 
 * @package MOE
 * @since 1.0
 */

(function($) {
    'use strict';

    /**
     * 页面加载完成
     */
    $(window).on('load', function() {
        // 移除加载动画
        $('.page-loader').addClass('loaded');
        setTimeout(function() {
            $('.page-loader').remove();
        }, 500);
        
        // 图片加载完成
        $('.post-thumbnail, .browser-view').addClass('loaded');
    });

    /**
     * 滚动显示动画
     */
    function initScrollReveal() {
        if ('IntersectionObserver' in window) {
            const reveals = document.querySelectorAll('.scroll-reveal');
            
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('revealed');
                        observer.unobserve(entry.target);
                    }
                });
            }, {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            });
            
            reveals.forEach(reveal => observer.observe(reveal));
        } else {
            // 降级方案
            $('.scroll-reveal').addClass('revealed');
        }
    }

    /**
     * 平滑滚动
     */
    function initSmoothScroll() {
        $('a[href*="#"]:not([href="#"])').click(function() {
            if (location.pathname === this.pathname && location.hostname === this.hostname) {
                var target = $(this.hash);
                target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
                if (target.length) {
                    $('html, body').animate({
                        scrollTop: target.offset().top - 80
                    }, 800, 'swing');
                    return false;
                }
            }
        });
    }

    /**
     * 图片加载完成移除占位符
     */
    function initImageLoading() {
        $('img').on('load', function() {
            $(this).parent().addClass('loaded');
        });
        
        // 处理已缓存的图片
        $('img').each(function() {
            if (this.complete) {
                $(this).parent().addClass('loaded');
            }
        });
    }

    /**
     * 移动端菜单切换
     */
    function initMobileMenu() {
        // 切换菜单显示/隐藏
        $('.nav-toggle').on('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            $('#nav-wrap').toggleClass('active');
            console.log('移动端菜单切换');
        });

        // 点击菜单项后关闭菜单
        $('#nav-wrap a').on('click', function() {
            if ($(window).width() <= 768) {
                $('#nav-wrap').removeClass('active');
            }
        });

        // 点击页面其他区域关闭菜单
        $(document).on('click', function(e) {
            if ($(window).width() <= 768) {
                if (!$(e.target).closest('#nav-wrap').length && 
                    !$(e.target).closest('.nav-toggle').length) {
                    $('#nav-wrap').removeClass('active');
                }
            }
        });
        
        // 窗口大小改变时，如果变大则关闭移动端菜单
        $(window).on('resize', function() {
            if ($(window).width() > 768) {
                $('#nav-wrap').removeClass('active');
            }
        });
    }

    /**
     * 平滑返回顶部
     */
    function initBackToTop() {
        $('#top').on('click', function(e) {
            e.preventDefault();
            $('html, body').animate({ scrollTop: 0 }, 600);
        });

        // 滚动时显示/隐藏返回顶部按钮
        $(window).on('scroll', function() {
            if ($(this).scrollTop() > 300) {
                $('#top').fadeIn();
            } else {
                $('#top').fadeOut();
            }
        });
    }

    /**
     * 导航栏固定
     */
    function initStickyNav() {
        var topbar = $('#topbar');
        var offset = 294;

        $(window).on('scroll', function() {
            if ($(this).scrollTop() > offset) {
                topbar.removeClass('affix-top').addClass('affix');
            } else {
                topbar.removeClass('affix').addClass('affix-top');
            }
        });
    }

    /**
     * 图片懒加载（如果需要）
     */
    function initLazyLoad() {
        if ('loading' in HTMLImageElement.prototype) {
            // 浏览器支持原生懒加载
            const images = document.querySelectorAll('img[loading="lazy"]');
            images.forEach(img => {
                img.src = img.dataset.src;
            });
        } else {
            // 使用 Intersection Observer
            if ('IntersectionObserver' in window) {
                const imageObserver = new IntersectionObserver(function(entries, observer) {
                    entries.forEach(function(entry) {
                        if (entry.isIntersecting) {
                            const image = entry.target;
                            image.src = image.dataset.src;
                            image.classList.remove('lazy');
                            imageObserver.unobserve(image);
                        }
                    });
                });

                const images = document.querySelectorAll('img.lazy');
                images.forEach(function(img) {
                    imageObserver.observe(img);
                });
            }
        }
    }

    /**
     * 评论表单增强
     */
    function initCommentForm() {
        // 评论表单验证
        $('#commentform').on('submit', function(e) {
            var comment = $('#comment').val().trim();
            var author = $('#author').val().trim();
            var email = $('#email').val().trim();

            if (comment === '') {
                alert('请输入评论内容！');
                $('#comment').focus();
                return false;
            }

            // 如果需要填写姓名和邮箱
            if ($('#author').prop('required') && author === '') {
                alert('请输入您的名字！');
                $('#author').focus();
                return false;
            }

            if ($('#email').prop('required') && email === '') {
                alert('请输入您的邮箱地址！');
                $('#email').focus();
                return false;
            }

            // 验证邮箱格式
            if (email !== '') {
                var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailPattern.test(email)) {
                    alert('请输入正确的邮箱格式！');
                    $('#email').focus();
                    return false;
                }
            }
        });

        // 评论回复功能增强
        $('.comment-reply-link').on('click', function(e) {
            var commentId = $(this).data('commentid');
            if (commentId) {
                $('#comment-pid').val(commentId);
                
                // 滚动到评论表单
                $('html, body').animate({
                    scrollTop: $('#comment-post').offset().top - 100
                }, 500);
            }
        });
    }

    /**
     * 响应式表格
     */
    function initResponsiveTables() {
        $('.entry table').each(function() {
            if (!$(this).parent().hasClass('table-responsive')) {
                $(this).wrap('<div class="table-responsive"></div>');
            }
        });
    }

    /**
     * 外部链接处理
     */
    function initExternalLinks() {
        $('a[href^="http"]').not('a[href*="' + location.hostname + '"]').attr({
            target: '_blank',
            rel: 'noopener noreferrer'
        });
    }

    /**
     * 搜索框焦点效果
     */
    function initSearchBox() {
        $('#nav_search_s').on('focus', function() {
            $(this).parent().addClass('search-focused');
        }).on('blur', function() {
            $(this).parent().removeClass('search-focused');
        });
    }

    /**
     * 动画效果初始化
     */
    function initAnimations() {
        // 文章卡片进入动画
        if ('IntersectionObserver' in window) {
            const animateObserver = new IntersectionObserver(function(entries) {
                entries.forEach(function(entry) {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('animated', 'fadeIn');
                        animateObserver.unobserve(entry.target);
                    }
                });
            }, {
                threshold: 0.1
            });

            document.querySelectorAll('.post').forEach(function(post) {
                animateObserver.observe(post);
            });
        }
    }

    /**
     * 代码高亮（如果使用 Prism.js 或 highlight.js）
     */
    function initCodeHighlight() {
        // 如果使用了代码高亮库，在这里初始化
        if (typeof Prism !== 'undefined') {
            Prism.highlightAll();
        } else if (typeof hljs !== 'undefined') {
            document.querySelectorAll('pre code').forEach(function(block) {
                hljs.highlightBlock(block);
            });
        }
    }

    /**
     * 分享功能（可选）
     */
    function initSocialShare() {
        $('.share-button').on('click', function(e) {
            e.preventDefault();
            var url = encodeURIComponent(window.location.href);
            var title = encodeURIComponent(document.title);
            var platform = $(this).data('platform');
            var shareUrl = '';

            switch(platform) {
                case 'weibo':
                    shareUrl = 'http://service.weibo.com/share/share.php?url=' + url + '&title=' + title;
                    break;
                case 'qq':
                    shareUrl = 'https://connect.qq.com/widget/shareqq/index.html?url=' + url + '&title=' + title;
                    break;
                case 'wechat':
                    // 微信分享需要二维码
                    alert('请使用微信扫描二维码分享');
                    return;
                case 'twitter':
                    shareUrl = 'https://twitter.com/intent/tweet?url=' + url + '&text=' + title;
                    break;
                case 'facebook':
                    shareUrl = 'https://www.facebook.com/sharer/sharer.php?u=' + url;
                    break;
            }

            if (shareUrl) {
                window.open(shareUrl, '_blank', 'width=600,height=400');
            }
        });
    }

    /**
     * 页面加载完成后初始化
     */
    $(document).ready(function() {
        // 基础功能
        initMobileMenu();
        initBackToTop();
        initStickyNav();
        initLazyLoad();
        initCommentForm();
        initResponsiveTables();
        initExternalLinks();
        initSearchBox();
        initAnimations();
        initCodeHighlight();
        initSocialShare();
        
        // 视觉美化
        initScrollReveal();
        initSmoothScroll();
        initImageLoading();

        // 隐藏返回顶部按钮
        $('#top').hide();
    });

    /**
     * 窗口大小改变时
     */
    $(window).on('resize', function() {
        // 如果窗口变大，移除移动端菜单的活动状态
        if ($(window).width() > 768) {
            $('#nav-wrap').removeClass('active');
        }
    });

})(jQuery);

