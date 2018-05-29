<template>
    <nav-view>
        <template slot="pane">
            <div class="navview-pane">
                <button class="pull-button">
                    <span class="default-icon-menu"></span>
                </button>
                <ul class="navview-menu">
                <li class="item-header">Jobs</li>
                <li class="item-separator"></li>
                <li>
                    <a href="#">
                        <span class="icon"><span class="mif-list-numbered"></span></span>
                        <span class="caption">List</span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <span class="icon"><span class="mif-note-add"></span></span>
                        <span class="caption">New Job</span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <span class="icon"><span class="mif-alarm"></span></span>
                        <span class="caption">TimeSheets</span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <span class="icon"><span class="mif-bin"></span></span>
                        <span class="caption">Trash</span>
                    </a>
                </li>
                
            </ul>
            </div>            
        </template>
        <template slot="content">
            <table class="table stripped row-hover">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Status</th>                    
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>1</td>
                    <td>Frontend Developer</td>
                    <td>some description gose here</td>
                    <td>active</td>
                    <div class="row-controls">
                        <button class="button primary outline mini">view</button>
                        <button class="button primary outline mini">edit</button>
                        <button class="button alert outline mini">delete</button>
                    </div>
                </tr>
                <tr>
                    <td>2</td>
                    <td>Find Requriments</td>
                    <td>some description gose here</td>
                    <td>active</td>
                    <div class="row-controls">
                        <button class="button primary outline mini">view</button>
                        <button class="button primary outline mini">edit</button>
                        <button class="button alert outline mini">delete</button>
                    </div>
                </tr>
                <tr>
                    <td>3</td>
                    <td>Css Styling</td>
                    <td>some description gose here</td>
                    <td>active</td>
                    <div class="row-controls">
                        <button class="button primary outline mini">view</button>
                        <button class="button primary outline mini">edit</button>
                        <button class="button alert outline mini">delete</button>
                    </div>
                </tr>
                <tr>
                    <td>4</td>
                    <td>Backend Developer</td>
                    <td>some description gose here</td>
                    <td>active</td>
                    <div class="row-controls">
                        <button class="button primary outline mini">view</button>
                        <button class="button primary outline mini">edit</button>
                        <button class="button alert outline mini">delete</button>
                    </div>
                </tr>
                <tr>
                    <td>5</td>
                    <td>Testing</td>
                    <td>some description gose here</td>
                    <td>active</td>
                    <div class="row-controls">
                        <button class="button primary outline mini">view</button>
                        <button class="button primary outline mini">edit</button>
                        <button class="button alert outline mini">delete</button>
                    </div>
                </tr>
                <tr>
                    <td>6</td>
                    <td>Database Designer</td>
                    <td>some description gose here</td>
                    <td>active</td>
                    <div class="row-controls">
                        <button class="button primary outline mini">view</button>
                        <button class="button primary outline mini">edit</button>
                        <button class="button alert outline mini">delete</button>
                    </div>
                </tr>
                </tbody>
            </table>
        </template>
    </nav-view>
</template>
<script>
import NavView from '../../../framework/NavView.vue';
export default {
    name: 'JobList',
    components:{
        'nav-view': NavView,
    }
}
</script>
<style scoped>
.table{
    background-color: white;    
}

.table tr{
    position: relative;
    transition: all 0.3s;
}

.table tr .row-controls{
    display: none;
    bottom: 2px;
    left: 90px;
    position: absolute;
}

.table tr .row-controls .button.mini{
    line-height: 10px !important;
}

.table tbody tr:hover{
    vertical-align: top;
    height: 60px;
}

.table tbody tr:hover td{
    vertical-align: top;   
}

.table tr:hover .row-controls{
    display: block;
}

</style>
