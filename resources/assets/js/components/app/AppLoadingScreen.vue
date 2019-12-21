<template>
  <div
    class="wrap-loading"
    v-show="show"
  >
    <div class="loading-box shadow-sm">
      <div>
        <div
          class="spinner-border"
          role="status"
        >
          <span class="sr-only">{{ message }}</span>
        </div>
      </div>
      <div class="caption">
        {{ message }}
      </div>
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
  z-index: 30;
  position: absolute;
  background-color: rgba(0, 0, 0, 0.15);
}

.loading-box {
  left: 50%;
  top: 50%;
  width: 200px;
  height: auto;
  padding: 10px;
  position: relative;
  display: flex;
  align-items: center;
  border-radius: 0px;
  transform: translate(-50%, -50%);
}

.loading-box .spinner-border {
  width: 20px;
  height: 20px;
  border: 3px solid currentColor;
  border-right-color: transparent;
}

.wrap-loading .caption {
  margin-left: 10px;
  text-align: left;
  font-size: 15px;
}
</style>