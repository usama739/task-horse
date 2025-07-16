import { useUser } from "@clerk/clerk-react";
import { useEffect } from "react";
import axios from "../axios";
import { useNavigate } from "react-router-dom";
import { useAuth } from '@clerk/clerk-react';

const AuthRedirect = () => {
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

          if (hasOrg) {
              navigate("/dashboard");
          } else {
              navigate("/create-organization");
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
