<template>
    <nav-view>
        <template slot="pane">
            <div class="navview-pane">
                <button class="pull-button">
                    <span class="default-icon-menu"></span>
                </button>
                <ul class="navview-menu">
                <li class="item-header">Job View</li>
                <li class="item-separator"></li>
                <li>
                    <a href="#" class="dropdown-toggle">
                        <span class="icon"><span class="mif-folder"></span></span>
                        <span class="caption">Jobs</span>
                    </a>
                    <ul class="navview-menu" data-role="dropdown">
                        <li>
                            <a href="#">
                                <span class="icon"><span class="mif-note-add"></span></span>
                                <span class="caption">New</span>
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <span class="icon"><span class="mif-list-numbered"></span></span>
                                <span class="caption">List</span>
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <span class="icon"><span class="mif-bin"></span></span>
                                <span class="caption">Trash</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="active">
                    <a href="#">
                        <span class="icon"><span class="mif-link"></span></span>
                        <span class="caption">Related Jobs</span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <span class="icon"><span class="mif-contacts-mail"></span></span>
                        <span class="caption">Related Contacts</span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <span class="icon"><span class="mif-calendar"></span></span>
                        <span class="caption">Schedule Items</span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <span class="icon"><span class="mif-books"></span></span>
                        <span class="caption">Activity Report</span>
                    </a>
                </li>
                
            </ul>
            </div>            
        </template>
        <template slot="content">
            <ul data-role="treeview">
                <li data-icon="<span class='mif-star-full'></span>" data-caption="Favorites">
                    <ul>
                        <li data-icon="<span class='mif-library'></span>" data-caption="Projects"></li>
                        <li data-icon="<span class='mif-download'></span>" data-caption="Downloads"></li>
                        <li data-icon="<img src='images/desktop.png'>" data-caption="Desktop"></li>
                    </ul>
                </li>
                <li data-icon="<span class='mif-onedrive'></span>" data-caption="OneDrive">
                    <ul>
                        <li data-caption="Documents"></li>
                        <li data-caption="Projects">
                            <ul>
                                <li data-caption="Web"></li>
                                <li data-caption="Android"></li>
                                <li data-caption="Windows"></li>
                                <li data-caption="iOS"></li>
                            </ul>
                        </li>
                    </ul>
                </li>
            </ul>
        </template>
    </nav-view>
</template>
<script>
import NavView from '../../../framework/NavView.vue';
export default {
    name: 'JobView',
    components:{
        'nav-view': NavView,
    }
}
</script>
<style scoped>

</style>
