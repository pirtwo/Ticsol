<script>
export default {
  name: "BaseTable",

  props: ["value"],

  data() {
    return {
      mode: "",
      selects: []
    };
  },

  methods: {
    getMode() {
      return this.mode;
    },

    setMode(mode) {
      this.mode = mode;
    },

    selectRow(obj) {
      if (!this.isSelected(obj)) this.selects.push(obj);
      this.onSelect(this.selects);
    },

    deSelectRow(obj) {
      if (this.isSelected(obj))
        this.selects.slice(this.selects.indexOf(obj), 1);
      this.onSelect(this.selects);
    },

    toggleRow(obj) {
      if (this.isSelected(obj)) {
        this.selects.splice(this.selects.indexOf(obj), 1);
      } else {
        this.selects.push(obj);
      }
      this.onSelect(this.selects);
    },

    deleteRow(obj) {
      this.onInput(this.value.filter(item => item !== obj));
    },

    isSelected(obj) {
      return this.selects.indexOf(obj) > -1;
    },

    onInput(data) {
      this.$emit("input", [...data]);
    },

    onSelect(data){
      this.$emit("select", [...data]);
    }
  },

  render() {
    return this.$scopedSlots.default({
      getMode: this.getMode,
      setMode: this.setMode,
      selects: this.selects,
      toggleRow: this.toggleRow,
      selectRow: this.selectRow,
      deSelectRow: this.deSelectRow,
      isSelected: this.isSelected,
      deleteRow: this.deleteRow
    });
  }
};
</script>
