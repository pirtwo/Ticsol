<template>
  <div
    ref="tsModal"
    :class="[{ 'fade': animation }, 'modal']"
    tabindex="-1"
    role="dialog"
  >
    <div
      role="document"
      :class="[{ 'modal-sm': size === 'sm', 'modal-lg': size === 'lg', 'modal-dialog-scrollable' : scrollable, 'modal-dialog-centered': centered }, 'modal-dialog']"      
    >
      <div class="modal-content">
        <div class="modal-header">
          <slot name="header">
            <!-- Default header -->
            <div class="d-flex justify-content-center align-items-center w-100">
              <ts-icon
                class="mr-2"
                v-if="icon"
                :icon="icon"
              />
              <h5 class="modal-title">
                {{ title }}
              </h5>
              <button
                type="button"
                class="close"
                @click="hideModal"
              >
                <span>&times;</span>
              </button>
            </div>
          </slot>
        </div>
        <div class="modal-body">
          <slot />
        </div>
        <div class="modal-footer">
          <slot name="footer" />
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "TsModal",

  props: {
    show: {
      value: Boolean,
      default: false
    },
    title: {
      value: String,
      default: ""
    },
    icon: {
      value: String,
      default: ""
    },
    size: {
      value: String,
      default: ""
    },
    centered: {
      type: Boolean,
      default: true
    },
    animation: {
      type: Boolean,
      default: true
    },
    backdrop: {
      type: [Boolean, String],
      default: true
    },
    scrollable: {
      type: Boolean,
      default: false
    }
  },

  watch: {
    show: function(value) {
      if (value) {
        $(this.$refs.tsModal).modal("show");
      } else {
        $(this.$refs.tsModal).modal("hide");
      }
    }
  },

  mounted() {
    $(this.$refs.tsModal).modal({
      show: this.show,
      backdrop: this.backdrop
    });

    $(this.$refs.tsModal).on("show.bs.modal", this.onShow);
    $(this.$refs.tsModal).on("hide.bs.modal", this.onHide);
    $(this.$refs.tsModal).on("shown.bs.modal", this.onShown);
    $(this.$refs.tsModal).on("hidden.bs.modal", this.onHidden);
  },

  methods: {
    hideModal(){
      $(this.$refs.tsModal).modal("hide");
    },

    onShow(e) {
      e.stopPropagation();
      this.$emit("update:show", true);
      this.$emit("onShow");
    },

    onHide(e) {
      e.stopPropagation();
      this.$emit("update:show", false);
      this.$emit("onHide");
    },

    onShown(e) {
      e.stopPropagation();
      this.$emit("shown");
    },

    onHidden(e) {
      e.stopPropagation();
      this.$emit("hidden");
    }
  }
};
</script>

<style scoped>
</style>
