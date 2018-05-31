<template>
    <nav-view>
        <template slot="pane">

        </template>
        <template slot="content">
            <form-builder></form-builder>
        </template>
    </nav-view>
</template>

<script>
import NavView from "../../../framework/NavView.vue";
import FormBuilder from "../../../framework/FormBuilder.vue";
export default {
  name: "JobProfile",
  components: {
    "nav-view": NavView,
    "form-builder": FormBuilder
  }
};
</script>

<style>
.cb-wrap {
  width: 26% !important;  
}
</style>
