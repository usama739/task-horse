import { create } from 'zustand';

interface User {
  id: number;
  name: string;
  email: string;
  role: 'admin' | 'member';
  organization_id: number | null;
}

interface UserStore {
  user: User | null;
  setUser: (user: User) => void;
  clearUser: () => void;
  isAdmin: () => boolean;
  isMember: () => boolean;
}

export const useUserStore = create<UserStore>((set, get) => ({
  user: null,

  setUser: (user) => set({ user }),

  clearUser: () => set({ user: null }),

  isAdmin: () => get().user?.role === 'admin',

  isMember: () => get().user?.role === 'member',
}));