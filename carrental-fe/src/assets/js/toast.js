export default {
 data: () => ({
  isLoading: false,
 }),
 methods: {
  toastData(status, data){
   if(status == 200){
    this.showToast(data.msg);
   }
   else {
    this.showToast(data, 'error');
   }
  },
  showToast(msg, alertType = 'success', isVisible = true) {
   const alert = {
    msg: msg,
    isVisible: isVisible,
    alertType: alertType,
   };
   this.$store.commit('alert/setAlert', alert);
  },
 }
}