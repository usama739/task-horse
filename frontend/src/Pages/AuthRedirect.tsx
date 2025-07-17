import { useUser } from "@clerk/clerk-react";
import { useEffect } from "react";
import axios from "../axios";
import { useNavigate } from "react-router-dom";
import { useAuth } from '@clerk/clerk-react';
import { useUserStore } from '../store/userStore';

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
          const res: any = await axios.get(`/users/${user.id}`, {
            headers: {
              Authorization: `Bearer ${token}`,
            },
          });
          const hasOrg = res.data.organization_id !== null;
          console.log("Member = ",isMember());
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
