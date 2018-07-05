<template>
    <div class="wrap">
        <input 
          v-model="input"       
          v-on:input="debounceOnInput" 
          v-on:change="debounceOnChange"
          v-on:focus="showList = true" 
          v-on:blur="debounceOnBlur"         
          class="form-control" 
          type="text" 
          name="" 
          v-bind:placeholder="placeHolder"/>
        <ul class="results" v-show="showList">
            <li class="result" 
                v-for="item in results" :key="item.key"
                v-on:click="onSelect(item.key, item.value)">
                {{ item.value }}
            </li>
        </ul>
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
      default: []
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
    placeHolder: {
      type: String,
      default: ""
    },
    value: {
      type: Number,
      default: null
    }
  },

  data() {
    return {
      input: "",
      results: this.data,
      selectedIndex: -1,
      showList: false
    };
  },

  watch: {
    data: function(value) {
      this.results = value;
    },

    value: function(value){
      if(value===-1){
        this.input = "";
      }
    }
  },

  created() {
    this.debounceOnBlur = _.debounce(this.onBlur, 200);
    this.debounceOnInput = _.debounce(this.onInput, 1000);
    this.debounceOnChange = _.debounce(this.onChange, 1000);
  },

  methods: {
    onInput() {
      this.showList = true;
      this.results = this.data.filter(
        item => item.value.toLowerCase().indexOf(this.input.toLowerCase()) > -1
      );
    },

    onChange(event) {
      if (this.data.indexOf(event.target.value) !== -1) {
        this.$emit(
          "input",
          this.data[this.data.indexOf(event.target.value)].key
        );
      }
    },

    onSelect(key, value) {
      this.input = value;
      this.selectedIndex = key;
      this.$emit("input", key);
      this.showList = false;
    },

    onBlur() {
      if ($(".wrap ul:focus").length === 0) {
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

ul {
  position: absolute;
  top: 100%;
  left: 0px;
  width: 100%;
  margin: 0px;
  padding: 5px;
  max-height: 100px;
  list-style: none;
  overflow-y: scroll;
  overflow-x: hidden;
  background-color: white;
  border-left: 2px solid rgba(60, 79, 247, 0.5);
  border-right: 2px solid rgba(60, 79, 247, 0.5);
  border-bottom: 2px solid rgba(60, 79, 247, 0.5);
  -webkit-box-shadow: 0px 3px 10px -2px rgba(0, 0, 0, 0.75);
  -moz-box-shadow: 0px 3px 10px -2px rgba(0, 0, 0, 0.75);
  box-shadow: 0px 3px 10px -2px rgba(0, 0, 0, 0.75);
  z-index: 99999;
}

ul li:hover {
  cursor: pointer;
  background-color: rgba(0, 0, 0, 0.05);
}
</style>
