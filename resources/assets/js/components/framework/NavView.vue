<template>
    <div data-role="navview">
        <div class="navview-pane">
            <slot name="pane">
                <ul class="navview-menu">
                    <li class="item-header">Main pages</li>
                    <li>
                        <a href="#">
                            <span class="icon">
                                <span class="mif-apps"></span>
                            </span>
                            <span class="caption">Apps</span>
                        </a>
                    </li>
                    <li class="item-separator"></li>
                    <li class="item-header">Office</li>
                    <li>
                        <a href="#">
                            <span class="icon">
                                <span class="mif-file-word"></span>
                            </span>
                            <span class="caption">Word</span>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <span class="icon">
                                <span class="mif-file-excel"></span>
                            </span>
                            <span class="caption">Excel</span>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <span class="icon">
                                <span class="mif-file-excel"></span>
                            </span>
                            <span class="caption">Excel</span>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <span class="icon">
                                <span class="mif-file-excel"></span>
                            </span>
                            <span class="caption">Excel</span>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <span class="icon">
                                <span class="mif-file-excel"></span>
                            </span>
                            <span class="caption">Excel</span>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <span class="icon">
                                <span class="mif-file-excel"></span>
                            </span>
                            <span class="caption">Excel</span>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <span class="icon">
                                <span class="mif-file-excel"></span>
                            </span>
                            <span class="caption">Excel</span>
                        </a>
                    </li>
                </ul>
            </slot>
        </div>
        <div class="navview-content">
            <div class="content">
                <slot name="content">
                    <router-link ></router-link>
                </slot>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        name: 'NavView',
    };
</script>
<style scoped>
</style>