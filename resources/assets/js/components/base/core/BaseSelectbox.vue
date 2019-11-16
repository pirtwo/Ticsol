<script>
export default {
  name: "BaseSelectbox",

  props: ["value", "data", "query", "regex", "flags", "multi"],

  data() {
    return {
      focusIndex: 0,
      dropdownStat: false
    };
  },

  watch: {
    multi: function(val) {
      if (val) {
        this.$emit("input", []);
      } else {
        this.$emit("input", null);
      }
    },

    data: function() {
      if(!this.isAnOption(this.value))
        this.clearSelects();
    }
  },

  computed: {
    regExp: function() {
      let exp = this.regex !== undefined ? this.regex : `(${this.query})+`;
      let flg = this.flags !== undefined ? this.flags : "g";
      return new RegExp(exp, flg);
    },

    options: function() {
      if (this.query.length === 0) return this.data;
      let reg = this.regExp;
      return this.data.filter(item => item.value.match(reg) !== null);
    }
  },

  methods: {
    highlightText(text, color) {
      if (this.query.length === 0) return text;
      let reg = this.regExp;
      return text.replace(reg, match => {
        return `<span style='color:${color};'>${match}</span>`;
      });
    },

    selectOption(option) {
      if (!this.isAnOption(option)) return;

      if (this.multi) {
        let index = this.findIndex(option);
        if (index < 0) this.$emit("input", [...this.value, option]);
      } else this.$emit("input", option);
    },

    deSelectOption(option) {
      if (!this.isAnOption(option)) return;

      if (this.multi) {
        let index = this.findIndex(option);
        if (index > -1) {
          this.$emit("input", [
            ...this.value.filter(item => item.key !== option.key)
          ]);
        }
      } else {
        this.$emit("input", null);
      }
    },

    toggleOption(option) {
      if (!this.isAnOption(option)) return;

      if (this.multi) {
        let index = this.findIndex(option);
        if (index < 0) {
          this.$emit("input", [...this.value, option]);
        } else {
          this.$emit("input", [
            ...this.value.filter(item => item.key !== option.key)
          ]);
        }
      } else {
        if (this.isSelected(option)) {
          this.$emit("input", null);
        } else {
          this.$emit("input", option);
        }
      }
    },

    isFocused(option){
      return this.data[this.focusIndex] === option;
    },

    isSelected(option) {
      // check for null
      if (!this.value) return false;

      if (this.multi) {
        let index = this.value.indexOf(
          this.value.find(item => item.key === option.key)
        );
        return index > -1;
      } else {
        return this.value.key === option.key;
      }
    },

    selectAll() {
      this.$emit("input", [...this.data]);
    },

    selectCurrent() {
      this.$emit("input", [...this.options]);
    },

    clearSelects() {
      if (this.multi) {
        this.$emit("input", []);
      } else {
        this.$emit("input", null);
      }
    },

    showDropdown() {
      this.dropdownStat = true;
    },

    hideDropdown() {
      this.dropdownStat = false;
    },

    toggleDropdown() {
      this.dropdownStat = !this.dropdownStat;
    },

    //==== Helpers ====//

    /**
     * check existence of given option.
     */
    isAnOption(option) {
      // check for null
      if (!option) return false;

      let index = this.data.indexOf(
        this.data.find(item => item.key === option.key)
      );
      return index > -1;
    },

    /**
     * Find the index of given option.
     */
    findIndex(option) {
      // check for null
      if (!option) return false;

      return this.value.indexOf(
        this.value.find(item => item.key === option.key)
      );
    },

    replacer(match, cssClass) {
      return `<span class='${cssClass}'>${match}</span>`;
    }
  },

  render() {
    return this.$scopedSlots.default({
      selects: this.value,
      options: this.options,
      dropdownStatus: this.dropdownStat,

      //==== functions ====

      // options
      isFocused: this.isFocused,
      isSelected: this.isSelected,
      selectAll: this.selectAll,
      selectCurrent: this.selectCurrent,
      clearSelects: this.clearSelects,
      toggleOption: this.toggleOption,
      selectOption: this.selectOption,
      deSelectOption: this.deSelectOption,
      highlightText: this.highlightText,

      // events
      onKeyDown: (e) => {
        if (!e.target) return;

        if (e.code === "ArrowUp") {
          this.focusIndex = (this.focusIndex - 1 < 0) ? this.data.length - 1 : this.focusIndex - 1; 
        }
        if (e.code === "ArrowDown") {
          this.focusIndex = (this.focusIndex + 1 >= this.data.length) ? 0 : this.focusIndex + 1; 
        }
        if (e.code === "Enter" || e.code === "Space") {
          this.toggleOption(this.data[this.focusIndex]);
        }
        if (e.code === "Escape") {
          this.hideDropdown();
        }
      },

      // dropdown
      showDropdown: this.showDropdown,
      hideDropdown: this.hideDropdown,
      toggleDropdown: this.toggleDropdown
    });
  }
};
</script>