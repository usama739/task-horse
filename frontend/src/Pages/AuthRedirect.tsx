import { useUser } from "@clerk/clerk-react";
import { useEffect } from "react";
import axios from "../axios";
import { useNavigate } from "react-router-dom";
import { useAuth } from '@clerk/clerk-react';
import { useUserStore } from '../store/userStore';

interface UserType {
  id: number;
  name: string;
  email: string;
  role: 'admin' | 'member';
  organization_id: number | null;
}

const AuthRedirect = () => {
  const isMember = useUserStore((state) => state.isMember);
  const { getToken } = useAuth();
  const { user, isSignedIn } = useUser();
  const navigate = useNavigate();

  useEffect(() => {
    const checkOrg = async () => {
      const token = await getToken();
      if (!token) return;

      if (isSignedIn && user) {
        try {
          const res = await axios.get<UserType>(`/users/${user.id}`, {
            headers: {
              Authorization: `Bearer ${token}`,
            },
          });
          const userData: UserType = res.data;
          const hasOrg = userData.organization_id !== null;
          
          if(isMember()){
              navigate("/tasks");
          } else {
            if (hasOrg) {
                navigate("/dashboard");
            } else {
                navigate("/create-organization");
            }
          }
          
        } catch (error) {
          console.error("Error checking organization:", error);
        }
      }
    };

    checkOrg();
  }, [user, isSignedIn]);

  return null; // or a loading spinner if you prefer
};

export default AuthRedirect;
