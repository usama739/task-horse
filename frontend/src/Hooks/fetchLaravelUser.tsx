// hooks/useAuthUser.ts
import { useEffect } from 'react';
import { useAuth } from '@clerk/clerk-react';
import axios from '../axios';
import { useUserStore } from '../store/userStore';

interface UserType {
  id: number;
  name: string;
  email: string;
  role: 'admin' | 'member';
  organization_id: number | null;
}

export const FetchLaravelUser = () => {
  const { getToken } = useAuth();
  const setUser = useUserStore((state) => state.setUser);

  useEffect(() => {
    const fetchUser = async () => {
      const token = await getToken();
      if (!token) return;
      
      try {
         const res = await axios.get<UserType>('/me', {
          headers: {
            Authorization: `Bearer ${token}`,
          },
        });

        setUser(res.data); 
      } catch (error) {
        console.error('Error fetching user:', error);
      }
    };
    

    fetchUser();
  }, []);
};
