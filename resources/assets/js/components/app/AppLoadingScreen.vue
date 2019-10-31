<template>
  <div class="wrap-loading" v-show="show">
    <div class="loading-box shadow-sm">
      <div>
        <div class="spinner-border" role="status">
          <span class="sr-only">{{ message }}</span>
        </div>
      </div>
      <div class="caption">{{ message }}</div>
    </div>
  </div>
</template>

<script>
export default {
  name: "AppLoadingScreen",

  props: {
    show: {
      type: Boolean,
      default: false
    },
    message: {
      type: String,
      default: "Loading, Please wait..."
    }
  }
};
</script>

<style scoped>
.wrap-loading {
  left: 0px;
  top: 0px;
  width: 100%;
  height: 100%;
  position: absolute;
  background-color: rgba(0, 0, 0, 0.15);
  z-index: 9;
}

.loading-box {
  left: 50%;
  top: 50%;
  width: 17vw;
  height: auto;
  padding: 1vw;
  position: relative;
  display: flex;
  align-items: center;
  border-radius: 0px;
  transform: translate(-50%, -50%);
  background-color: white;
}

.loading-box .spinner-border {
  width: 2vw;
  height: 2vw;
  border: 0.4vw solid currentColor;
  border-right-color: transparent;
}

.wrap-loading .caption {
  margin-left: 10px;
  display: inline;
  text-align: left;
  vertical-align: super;
}
</style>