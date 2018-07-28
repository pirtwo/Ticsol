<template>

    <div class="wrap" ref="BaseSelectBox">

        <input type="text" class="select-value form-control"
            v-model="text"
            @focus="onFocus"
            :name="name" 
            :placeholder="placeholder" 
            readonly>

        <button class="btn btn-sm btn-link" @click.stop="showList = !showList">
            <i class="material-icons">
                {{ showList? "expand_less": "expand_more"}}
            </i>
        </button>

        <div class="wrap-list" v-show="showList">
            <input type="text" name="" class="select-query form-control form-control-sm"
                    v-model="query" 
                    :placeholder="searchPlaceholder">
            
            <ul class="select-options">            
                <li v-for="item in options" 
                    :key="item.key" 
                    :class="[{'selected' : isSelected(item)}, 'select-option']"
                    @click="onSelect(item, $event)">

                    <input type="checkbox" v-if="multiSelect" :checked="isSelected(item)"> 
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
    placeholder: {
      type: String,
      default: ""
    },
    searchPlaceholder: {
      type: String,
      default: ""
    },
    multiSelect: {
      type: Boolean,
      default: false
    },
    value: {
      type: [Array, Object],
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
    }
  },

  computed: {
    options: function() {
      return this.data.filter(
        item => item.value.toLowerCase().indexOf(this.query.toLowerCase()) > -1
      );
    }
  },

  methods: {
    onFocus(e) {
      this.showList = true;
      $(".select-value").blur();
      e.preventDefault();
    },

    searchFocus() {
      $(".select-query").focus();
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
        this.$emit("input", this.selects);
      } else {
        this.text = item.value;
        this.selects = [];
        this.selects.push(item);
        this.$emit("input", this.selects[0]);
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
  border-bottom: 0px !important;
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
  padding: 3px 10px 10px 10px;
  margin-top: 0px;
  background-color: white;
  border: 1px solid #ced4da;
  border-top: 0px;
  border-radius: 0px 0px 5px 5px;
  /* box-shadow: 0px 3px 10px -2px rgba(0, 0, 0, 0.75);
  -moz-box-shadow: 0px 3px 10px -2px rgba(0, 0, 0, 0.75);
  -webkit-box-shadow: 0px 3px 10px -2px rgba(0, 0, 0, 0.75); */
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
  padding: 3px 10px;
  cursor: pointer;
  border-radius: 4px;
  transition: all 0.3s cubic-bezier(0.075, 0.82, 0.165, 1);
}

ul li:last-child {
  margin-bottom: 5px;
}

ul li.selected {
  background-color: yellow;
}

ul li::selection {
  background-color: transparent;
}

ul li:hover {
  color: white;
  box-shadow: 0px 3px 5px -2px rgba(0, 0, 0, 0.75);
  background-color: rgba(41, 62, 129, 0.671);
}
</style>
