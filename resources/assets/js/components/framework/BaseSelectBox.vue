<template>
  <div 
    class="wrap" 
    ref="BaseSelectBox"
  >
    <input 
      type="text" 
      :class="[inputSize, 'form-control select-value']"
      v-model="text"
      @focus="onFocus"
      :name="name" 
      :id="id"
      :placeholder="placeholder" 
      readonly
    >

    <button 
      type="button" 
      class="btn btn-sm btn-link" 
      @click.stop="showList = !showList"
    >
      <i class="material-icons">
        {{ showList? "expand_less": "expand_more" }}
      </i>
    </button>

    <div 
      class="wrap-list" 
      v-show="showList"
    >
      <input 
        type="text" 
        name="" 
        class="searchbox form-control form-control-sm"
        v-model="query" 
        v-if="enableSearchbox"
        :placeholder="searchPlaceholder"
      >
            
      <ul class="select-options"> 
        <slot name="default-options" />           
        <li 
          v-for="item in options" 
          :key="item.key" 
          :class="[{'selected' : isSelected(item)}, 'select-option']"
          @click="onSelect(item, $event)"
        >
          <input 
            type="checkbox" 
            v-if="multiSelect" 
            :checked="isSelected(item)"
          >                     
          {{ item.value }}
        </li>
      </ul>
    </div>
  </div>
</template>

<script>
import _ from "lodash";

export default {
  name: "BaseSelectBox",

  props: {
    data: {
      type: Array,
      required: true,
      default: []
    },
    name: {
      type: String,
      required: true,
      default: ""
    },
    id: {
      type: String,
      default: ""
    },
    size: {
      type: String,
      default: "md",
      validator: value => {
        return ["lg", "md", "sm"].indexOf(value) !== -1;
      }
    },
    placeholder: {
      type: String,
      default: ""
    },
    enableSearchbox: {
      type: Boolean,
      default: true
    },
    searchPlaceholder: {
      type: String,
      default: ""
    },
    multiSelect: {
      type: Boolean,
      default: false
    },
    outputType: {
      type: String,
      default: "key",
      validator: value => {
        return ["key", "value", "key-value"].indexOf(value) !== -1;
      }
    },
    default: {
      type: [Object, Number],
      default: null
    },
    value: {
      type: [Array, Object, Number, String],
      default: null
    }
  },

  data() {
    return {
      text: "",
      query: "",
      selects: [],
      showList: false
    };
  },

  created() {
    document.addEventListener("click", this.onDocumentClick);
  },

  mounted() {
    this.debounceOnFocus = _.debounce(this.searchFocus, 200);
  },

  destroyed() {
    document.removeEventListener("click", this.onDocumentClick);
  },

  watch: {
    showList: function(value) {
      if (value) {
        this.debounceOnFocus();
        $(".select-value").addClass("list-open");
      } else {
        $(".select-value").removeClass("list-open");
      }
    },

    value: function(value) {
      if (value === null) {
        this.text = "";
        this.query = "";
        this.selects = [];
      }
    },

    default: function(value) {
      this.selects = [];
      let item = this.data.find(item => item.key == value);
      this.text = item.value;
      this.selects.push(item);
    }
  },

  computed: {
    options: function() {
      return this.data.filter(
        item => item.value.toLowerCase().indexOf(this.query.toLowerCase()) > -1
      );
    },

    inputSize: function() {
      if (this.size == "lg") return "form-control-lg";
      else if (this.size == "md") return "";
      else if (this.size == "sm") return "form-control-sm";
    }
  },

  methods: {
    onFocus(e) {
      this.showList = true;
      $(".select-value").blur();
      e.preventDefault();
    },

    searchFocus() {
      $(".searchbox").focus();
    },

    onSelect(item, e) {
      let checkbox = {};
      let status = false;

      if (this.multiSelect) {
        if (e.target.tagName === "LI") {
          checkbox = $(e.target).find("input");
          status = $(checkbox).prop("checked");
        } else if (e.target.tagName === "INPUT") {
          checkbox = $(e.target);
          status = !$(checkbox).prop("checked");
        }

        if (status) {
          this.selects.splice(
            this.selects.findIndex(el => el.key === item.key),
            1
          );
          $(e.target).removeClass("selected");
        } else {
          this.selects.push(item);
          $(e.target).addClass("selected");
        }

        $(checkbox).prop("checked", !status);
        this.text = this.selects.map(el => el.value).join(" | ");
        this.update(this.selects);
      } else {
        if (this.isSelected(item)) {
          this.text = "";
          this.selects = [];
          this.$emit("input", null);
        } else {
          this.text = item.value;
          this.selects = [];
          this.selects.push(item);
          this.update(this.selects[0]);
        }
      }
    },

    update(value) {
      if (this.outputType === "key")
        this.$emit(
          "input",
          Array.isArray(value)
            ? value.map(item => {
                return item.key;
              })
            : value.key
        );
      if (this.outputType === "value") {
        this.$emit(
          "input",
          Array.isArray(value)
            ? value.map(item => {
                return item.value;
              })
            : value.value
        );
      }
      if (this.outputType === "key-value") {
        this.$emit("input", value);
      }
    },

    isSelected(item) {
      return (
        this.selects.find(
          el => el.key === item.key && el.value === item.value
        ) !== undefined
      );
    },

    onDocumentClick(e) {
      let el = this.$refs.BaseSelectBox;
      let target = e.target;
      if (el !== target && !el.contains(target)) {
        this.showList = false;
      }
    }
  }
};
</script>

<style scoped>
.wrap {
  position: relative;
}

.select-value {
  background-color: white;
}

.list-open {
  border-bottom-left-radius: 0px !important;
  border-bottom-right-radius: 0px !important;
}

.wrap-list {
  display: block;
  overflow: show;
  position: absolute;
  top: 100%;
  left: 0px;
  width: 100%;
  z-index: 10;
  padding: 10px 10px 10px 10px;
  margin-top: 0px;
  background-color: white;
  border: 1px solid #ced4da;
  border-top: 0px;
  border-radius: 0px 0px 5px 5px;
  /* box-shadow: 0px 3px 10px -2px rgba(0, 0, 0, 0.75);
  -moz-box-shadow: 0px 3px 10px -2px rgba(0, 0, 0, 0.75);
  -webkit-box-shadow: 0px 3px 10px -2px rgba(0, 0, 0, 0.75); */
}

.highlight {
  background-color: yellow;
}

button {
  top: 0px;
  right: 0px;
  position: absolute;
}

button i {
  line-height: 1.2;
}

ul {
  margin-top: 10px;
  padding-left: 0px;
  max-height: 150px;
  margin-bottom: 5px;
  overflow-y: auto;
  overflow-x: hidden;
  list-style-type: none;
}

ul li {
  margin: 0px 5px;
  padding: 1px 10px;
  cursor: pointer;
  border-radius: 4px;
  transition: all 0.3s cubic-bezier(0.075, 0.82, 0.165, 1);
}

ul li:last-child {
  margin-bottom: 5px;
}

ul li.selected {
  border-radius: 0px;
  background-color: rgba(000, 000, 000, 0.1);
}

ul li::selection {
  background-color: transparent;
}

ul li:hover {
  color: white;
  background-color: rgba(41, 62, 129, 0.671);
}
</style>
