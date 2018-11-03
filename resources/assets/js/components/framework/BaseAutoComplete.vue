<template>
  <div 
    class="wrap" 
    ref="BaseAutoComplete">
    <input 
      v-model="input"          
      @change="debounceOnChange"
      @focus="showList = true"         
      type="text"
      :class="[{ 'list-open' : showList }, 'form-control']"
      :id="id" 
      :name="name" 
      :placeholder="placeholder">
    <div 
      class="wrap-results" 
      v-show="showList">
      <ul class="results">
        <li 
          class="result" 
          v-for="item in options" 
          :key="item.key"
          @click="onSelect(item.key, item.value)">
          {{ item.value }}
        </li>
      </ul>
    </div>        
  </div>
</template>

<script>
import _ from "lodash";
export default {
  name: "BaseAutoComplete",

  props: {
    data: {
      type: Array,
      required: true,
      default: ()=>{ return []}
    },
    id: {
      type: String,
      default: ""
    },
    name: {
      type: String,
      required: true,
      default: ""
    },
    lable: {
      type: String,
      default: ""
    },
    placeholder: {
      type: String,
      default: ""
    },
    value: {
      type: [Number, Object],
      default: null
    }
  },

  data() {
    return {
      input: "",
      selectedIndex: -1,
      showList: false
    };
  },

  created() {
    this.debounceOnChange = _.debounce(this.onChange, 1000);
    document.addEventListener("click", this.onDocumentClick);
  },

  destroyed() {
    document.removeEventListener("click", this.onDocumentClick);
  },

  watch: {
    value: function(value) {
      if (value === null) {
        this.input = "";
      }
    }
  },

  computed: {
    options: function() {
      return this.data.filter(
        item => item.value.toLowerCase().indexOf(this.input.toLowerCase()) > -1
      );
    }
  },

  methods: {
    onChange(event) {
      if (this.data.indexOf(event.target.value) !== -1) {
        this.$emit(
          "input",
          this.data[this.data.indexOf(event.target.value)].key
        );
      } else if (this.input === "") {
        this.$emit("input", null);
      }
    },

    onSelect(key, value) {
      this.input = value;
      this.selectedIndex = key;
      this.$emit("input", key);
      this.showList = false;
    },

    onDocumentClick(e) {
      let el = this.$refs.BaseAutoComplete;
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

input[type="text"] {
  width: 100%;
}

input[type="text"]:focus {
  box-shadow: none;
  border-color: #ced4da;
}

.wrap-results {  
  top: 100%;
  left: 0px;
  width: 100%;
  z-index: 10;
  padding: 5px;
  margin-top: 0px;
  position: absolute;
  background-color: white;
  border: 1px solid #ced4da;
  border-radius: 0px 0px 0.25rem 0.25rem;      
}

.list-open {
  border-bottom: 0px !important;
  border-bottom-left-radius: 0px !important;
  border-bottom-right-radius: 0px !important;
}

ul {
  margin: 0px;
  padding: 5px;
  list-style: none;
  overflow-y: auto;
  overflow-x: hidden;
  max-height: 150px;
}

ul li{
  margin: 0px 5px;
  padding: 1px 10px;
  cursor: pointer;
  border-radius: 4px;
  transition: all 0.3s cubic-bezier(0.075, 0.82, 0.165, 1);
}

ul li:hover {
  cursor: pointer;
  color: white;  
  background-color: rgba(41, 62, 129, 0.671);
}
</style>
