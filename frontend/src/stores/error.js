import { ref } from "vue";

export const errorMessage = ref("");

export const setError = (msg) => {
  errorMessage.value = msg;

  setTimeout(() => {
    errorMessage.value = "";
  }, 3000);
};