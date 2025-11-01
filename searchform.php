<?php
/**
 * Template for displaying search forms
 * 
 * @package MOE
 * @since 1.0
 */
?>

<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
    <label>
        <span class="screen-reader-text"><?php _e( '搜索：', 'moe' ); ?></span>
        <input 
            type="search" 
            class="search-field" 
            placeholder="<?php esc_attr_e( '搜索...', 'moe' ); ?>" 
            value="<?php echo get_search_query(); ?>" 
            name="s" 
        />
    </label>
    <button type="submit" class="search-submit">
        <i class="fa fa-search"></i>
        <span class="screen-reader-text"><?php _e( '搜索', 'moe' ); ?></span>
    </button>
</form>

<style>
.search-form {
    position: relative;
    display: flex;
    max-width: 100%;
}

.search-form label {
    flex: 1;
    margin: 0;
}

.search-field {
    width: 100%;
    padding: 10px 50px 10px 15px;
    border: 2px solid #ddd;
    border-radius: 25px;
    font-size: 14px;
    transition: all 0.3s ease;
}

.search-field:focus {
    outline: none;
    border-color: #24a5db;
    box-shadow: 0 0 5px rgba(36, 165, 219, 0.3);
}

.search-submit {
    position: absolute;
    right: 5px;
    top: 50%;
    transform: translateY(-50%);
    background: #24a5db;
    color: #fff;
    border: none;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    cursor: pointer;
    transition: all 0.3s ease;
}

.search-submit:hover {
    background: #1a8fbd;
    transform: translateY(-50%) scale(1.1);
}

.screen-reader-text {
    position: absolute;
    width: 1px;
    height: 1px;
    padding: 0;
    margin: -1px;
    overflow: hidden;
    clip: rect(0, 0, 0, 0);
    white-space: nowrap;
    border-width: 0;
}
</style>

