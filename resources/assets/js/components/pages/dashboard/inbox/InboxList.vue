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
            <ul data-role="listview" data-view="content" data-selectable="true">
                <li data-icon="<span class='mif-stack2'>"
                    data-caption="Leave Request: Submited"
                    data-content="<span class='text-muted'>Created: 12.21.2017</span>"
                    v-on:click="clickHandler">
                </li>
                <li data-icon="<span class='mif-stack2'>"
                    data-caption="Leave Request"
                    data-content="<span class='text-muted'>Created: 12.21.2017</span>">
                </li>
                <li data-icon="<span class='mif-stack2'>"
                    data-caption="Leave Request"
                    data-content="<span class='text-muted'>Created: 12.21.2017</span>">
                </li>
                <li data-icon="<span class='mif-stack2'>"
                    data-caption="Leave Request"
                    data-content="<span class='text-muted'>Created: 12.21.2017</span>">
                </li>
                <li data-icon="<span class='mif-stack2'>"
                    data-caption="Leave Request"
                    data-content="<span class='text-muted'>Created: 12.21.2017</span>">
                </li>
                <li data-icon="<span class='mif-stack2'>"
                    data-caption="Leave Request"
                    data-content="<span class='text-muted'>Created: 12.21.2017</span>">
                </li>
            </ul>
        </template>
    </nav-view>
</template>
<script>
import NavView from "../../../framework/NavView.vue";
export default {
  name: "InboxList",
  components: {
    "nav-view": NavView
  },
  methods: {
    clickHandler() {
      console.log("click");
    }
  }
};
</script>
<style scoped>
</style>
