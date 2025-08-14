// hooks/useAuthUser.ts
import { useEffect } from 'react';
import { useAuth } from '@clerk/clerk-react';
import axios from '../axios';
import { useUserStore } from '../store/userStore';


export const fetchLaravelUser = () => {
  const { getToken } = useAuth();
  const setUser = useUserStore((state) => state.setUser);

  useEffect(() => {
    const fetchUser = async () => {
      const token = await getToken();
      if (!token) return;
      
      try {
         const res: any = await axios.get('/me', {
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
