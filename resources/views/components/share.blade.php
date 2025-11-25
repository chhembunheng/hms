@props(['url' => request()->url(), 'label' => __('global.share'), 'title' => config('app.name')])
<h4>{{ $label }}</h4>
<ul class="social-share-list">
    <li>
        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($url) }}" class="share-fb" target="_blank"
            aria-label="Share on Facebook">
            <i class="fab fa-facebook-f"></i>
        </a>
    </li>
    <li>
        <a href="https://twitter.com/intent/tweet?url={{ urlencode($url) }}" class="share-x" aria-label="Share on X"
            target="_blank">
            <i class="fab fa-x"></i>
        </a>
    </li>
    <li>
        <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode($url) }}" class="share-li"
            target="_blank" aria-label="Share on LinkedIn">
            <i class="fab fa-linkedin-in"></i>
        </a>
    </li>
    <li>
        <a href="mailto:?subject={{ $title }}&body={{ $url }}" class="share-li"
            aria-label="Share on Email">
            <i class="fa fa-envelope"></i>
        </a>
    </li>
</ul>
<style>
    .social-share-list {
        list-style: none;
        padding: 0;
        display: flex;
        gap: 10px;
    }

    .social-share-list li a {
        color: #0e1e67;
        display: inline-block;
        background: #fff0;
        width: 35px;
        height: 35px;
        line-height: 35px;
        text-align: center;
        border: 1px dashed #0e1e67;
        border-radius: 25px;
    }

    .social-share-list li a:hover {
        background-color: #0e1e67;
        color: #fff;
        text-decoration: none;
    }
</style>
