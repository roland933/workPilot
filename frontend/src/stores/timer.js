import { ref } from "vue";
import api from "../api";

export const activeTimer = ref(null);

export const loadActiveTimer = async () => {
  const res = await api.get("/time/active");
  activeTimer.value = res.data || null;
};