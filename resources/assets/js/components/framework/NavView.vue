<template>
    <div data-role="navview" data-expanded="lg" data-compact="md">
        <div class="navview-pane">
            <button class="pull-button" v-if="pullButton">
                <span class="default-icon-menu"></span>
            </button>
            <slot name="pane">               
            </slot>
        </div>
        <div class="navview-content">
            <div class="content-wrap" v-bind:class="[scrollbar ? '': 'hide-scrollbar']">
                <div class="content-loading" v-show="loadingContent">
                    <div class="icon" data-role="activity" data-type="square" data-style="color"></div>
                    <div class="text-light">Loading, Please Wait...</div>
                </div>
                <div class="content" v-show="!loadingContent">
                    <slot name="content">
                        <router-link ></router-link>
                    </slot>
                </div>                
            </div>
        </div>
    </div>
</template>
<script>
export default {
  name: "NavView",
  props: {
    scrollbar: {
      type: Boolean,
      default: true
    },
    loadingContent:{
        type: Boolean,
        default: false
    },
    loadingPane:{
        type: Boolean,
        default: false
    },
    pullButton:{
        type: Boolean,
        default: true
    }
  }  
};
</script>
<style scoped>
.hide-scrollbar {
  overflow-y: hidden !important;
}

.content{
    height: 100%;
}

.content-loading {
  top: 40%;
  left: 40%;
  width: 20%;
  padding: 10px; 
  text-align: center;
  position: absolute;
  background-color: aliceblue;
}

.content-loading .icon {
  margin-left: auto;
  margin-right: auto;
}
</style>