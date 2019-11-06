<template>
  <div 
    ref="vbSelect" 
    class="ts-select"
  >
    <input      
      ref="input"
      type="text"
      :class="[`form-control-${size}`,'ts-select__input form-control']"
      :value="inputText"
      :placeholder="placeholder"
      @focus="focusHandler"
    >

    <!-- toggole btn-->
    <button
      type="button"
      class="ts-select__toggleBtn btn btn-sm btn-link"
      @click="dropdownStatus = !dropdownStatus"
    >
      <i class="material-icons">{{ dropdownStatus ? 'expand_less' : 'expand_more' }}</i>
    </button>

    <!-- dropdown -->
    <div 
      ref="dropdown" 
      v-show="dropdownStatus" 
      class="ts-select__dropdown"
    >
      <!-- searchbox -->
      <input
        ref="searchbox"
        type="text"
        class="ts-select__dropdown__search form-control form-control-sm"
        v-model="searchQuery"
        v-if="searchEnable"
        :placeholder="searchPlaceholder"
      >
      <!-- END searchbox -->
      <!-- options-->
      <ul class="ts-select__dropdown__list">
        <slot 
          v-if="options.length != 0" 
          name="fixed-top"
        />
        <li
          v-for="(value, index) in options"
          :key="index"
          :class="[{ 'ts-select__dropdown__list__option--selected': isSelected(value) }]"
          @click="selectHandler(value, $event)"
        >
          <slot :item="value">
            {{ value.value }}
          </slot>
        </li>
        <slot 
          v-if="options.length != 0"
          name="fixed-bottom"
        />
        <slot 
          v-if="options.length === 0" 
          name="notfound-message"
        />
      </ul>
      <!-- END options -->
    </div>
    <!-- END dropdown-->
  </div>
  <!-- END ts-select -->
</template>

<script>
export default {
  name: "VbSelect",

  props: {
    value: {
      type: [Array, Object],
      default: () => {
        return {};
      }
    },
    data: {
      type: Array,
      default: () => {
        return [];
      }
    },
    multi: {
      type: Boolean,
      default: false
    },
    id: {
      type: String,
      default: ""
    },
    name: {
      type: String,
      default: ""
    },
    size: {
      type: String,
      default: "md",
      validator: function(value) {
        return ["sm", "md", "lg"].indexOf(value.toLowerCase()) > -1;
      }
    },
    placeholder: {
      type: String,
      default: ""
    },
    searchEnable: {
      type: Boolean,
      default: true
    },
    searchPlaceholder: {
      type: String,
      default: "search..."
    }
  },

  data() {
    return {
      selects: this.multi ? this.value : Array(this.value),
      dropdownStatus: false,
      searchQuery: ""
    };
  },

  watch: {
    value: function(value) {
      if(!value) this.selects = [];
      if (this.multi) {
        this.selects = [...value];
      } else {
        if (!value) return;
        this.selects = [];
        this.selects.push(value);
      }
    },

    data: function(value) {
      this.selects = [];
    },

    multi: function(value) {
      this.selects = [];
    },

    dropdownStatus: function(value) {
      if (value) {
        this.$refs.dropdown.classList.remove("ts-select__dropdown--close");
        this.$refs.dropdown.classList.add("ts-select__dropdown--open");
      } else {
        this.$refs.dropdown.classList.remove("ts-select__dropdown--open");
        this.$refs.dropdown.classList.add("ts-select__dropdown--close");
      }
      this.searchQuery = "";
    }
  },

  computed: {
    options: function() {
      return this.data.filter(
        item =>
          item.value.toLowerCase().indexOf(this.searchQuery.toLowerCase()) > -1
      );
    },

    inputText: function() {
      if (this.multi) {
        if (this.selects.length === 0) return "";
        return `${this.selects.length} item selected`;
      } else {
        if (!this.selects[0]) return "";
        return this.selects[0].value;
      }
    }
  },

  created() {
    document.addEventListener("click", this.docClickHandler);
  },

  mounted() {
    //
  },

  destroyed() {
    document.removeEventListener("click", this.docClickHandler);
  },

  methods: {
    selectHandler(item, event) {
      if (this.multi) {
        if (!this.isSelected(item)) {
          this.selects.push(item);          
        } else {
          this.selects.splice(
            this.selects.findIndex(
              fn => fn.key === item.key && fn.value === item.value
            ),
            1
          );          
        }
        this.$emit("input", [...this.selects]);
        this.$emit("change", [...this.selects]);
      } else {
        this.dropdownStatus = false;
        this.selects = [];
        this.selects.push(item);
        this.$emit("input", this.selects[0]);
        this.$emit("change", this.selects[0]);
      }      
    },

    focusHandler(event) {
      this.dropdownStatus = true;
      this.$refs.input.blur();
      if (this.searchEnable) {
        this.$refs.searchbox.focus();
      }
    },

    isExist(option) {
      return (
        this.data.find(
          item => item.key === option.key && item.value === option.value
        ) != null
      );
    },

    isSelected(option) {
      return (
        this.selects.find(
          item => item.key === option.key && item.value === option.value
        ) != null
      );
    },

    docClickHandler(event) {
      let selectbox = this.$refs.vbSelect;
      let target = event.target;
      if (selectbox !== target && !selectbox.contains(target)) {
        this.dropdownStatus = false;
      }
    }
  }
};
</script>

<style lang="scss" scoped>
.ts-select {
  position: relative;
  padding: 0px;
}

.is-invalid {
  padding-right: 4rem !important;
  background-position: center right 2rem;
}

.is-invalid .ts-select__input{
  border: 0px !important;
  box-shadow: none !important;
  height: calc(1.5em + 0.75rem - 2px);
}

.ts-select__input {
  cursor: pointer;
}

.ts-select__toggleBtn {
  top: 0px;
  right: 0px;
  height: 100%;
  position: absolute;
}

.ts-select__toggleBtn i {
  line-height: 100%;
}

.ts-select__dropdown {
  display: block;
  overflow: show;
  position: absolute;
  top: 100%;
  left: 0px;
  width: 100%;
  z-index: 10;
  margin-top: 0px;
  border-top: 0px;
  background-color: white;
  border: 1px solid #ced4da;
  padding: 10px 10px 10px 10px;
  border-radius: 0px 0px 5px 5px;
}

.ts-select__dropdown--open {
  animation-name: dropdown-open;
  animation-duration: 0.3s;
  opacity: 1;
}

.ts-select__dropdown--close {
  animation-name: dropdown-close;
  animation-duration: 0.3s;
  opacity: 0;
}

@keyframes dropdown-open {
  from {
    top: -30px;
    opacity: 0;
  }
  to {
    top: 100%;
    opacity: 1;
  }
}

@keyframes dropdown-close {
  from {
    top: 100%;
    opacity: 1;
  }
  to {
    top: -30px;
    opacity: 0;
  }
}

.ts-select__dropdown__list {
  margin-top: 10px;
  max-height: 150px;
  padding-left: 0px;
  padding-bottom: 7px;
  margin-bottom: 5px;
  overflow-y: auto;
  overflow-x: hidden;
  list-style-type: none;
}

.ts-select__dropdown__list li {
  text-align: left;
  cursor: pointer;
  margin: 0px 5px;
  padding: 5px 10px;
  border-radius: 0px;
}

.ts-select__dropdown__list li:last-child {
  margin-bottom: 5px;
}

.ts-select__dropdown__list li:hover {
  color: white;
  background-color: rgba(41, 62, 129, 0.671);
}

.ts-select__dropdown__list__option--selected {
  background-color: rgba(0, 0, 0, 0.1);
}
</style>
