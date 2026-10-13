<template>
  <div class="w-3/4 mx-auto flex justify-start ">
  <button @click="exportToExcel" class="bg-primary-button rounded-lg px-6 py-1 hover:bg-hover2">
    تصدير  
  </button>
  </div>
</template>

<script>
import * as XLSX from 'xlsx';

export default {
  props: ['data', 'fileName'],
  methods: {
    exportToExcel() {
      const worksheet = XLSX.utils.json_to_sheet(this.data);
      const workbook = XLSX.utils.book_new();
      XLSX.utils.book_append_sheet(workbook, worksheet, "البيانات");
      XLSX.writeFile(workbook, this.fileName);
    }
  }
};
</script>

