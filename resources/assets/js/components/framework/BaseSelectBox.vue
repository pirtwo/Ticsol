<template>

    <div class="wrap" ref="BaseSelectBox">

        <input type="text" name="" class="select-value form-control"
            v-model="text"
            @focus="onFocus"
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
                <li class="select-option" v-for="item in options" 
                    :key="item.key" 
                    @click="onSelect(item, $event)">

                    <input type="checkbox" v-if="multiSelect"> 
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

  destroyed() {
    document.removeEventListener("click", this.onDocumentClick);
  },

  watch: {
    showList: function(value) {
      if (value) this.debounceOnFocus();
    }
  },

  computed: {
    options: function() {
      return this.data.filter(
        item => item.value.toLowerCase().indexOf(this.query.toLowerCase()) > -1
      );
    }
  },

  mounted() {
    this.debounceOnFocus = _.debounce(this.searchFocus, 200);
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
        } else {
          this.selects.push(item);
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

.wrap-list {
  display: block;
  position: absolute;
  top: 100%;
  left: 0px;
  width: 100%;
  margin-top: 3px;
  z-index: 10;
  padding: 10px;
  background-color: white;
  border-radius: 0px 0px 5px 5px;
  box-shadow: 0px 3px 10px -2px rgba(0, 0, 0, 0.75);
  -moz-box-shadow: 0px 3px 10px -2px rgba(0, 0, 0, 0.75);
  -webkit-box-shadow: 0px 3px 10px -2px rgba(0, 0, 0, 0.75);
}

.select-query {
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
  padding-left: 0px;
  margin-top: 5px;
  margin-bottom: 0px;
  list-style-type: none;
}

ul li {
  cursor: pointer;
}

ul li::selection {
  background-color: transparent;
}

ul li:hover {
  background-color: rgba(0, 0, 0, 0.05);
}
</style>
