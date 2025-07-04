import { useUser } from "@clerk/clerk-react";
import { useEffect } from "react";
import axios from "axios";
import { useNavigate } from "react-router-dom";

const AuthRedirect = () => {
  const { user, isSignedIn } = useUser();
  const navigate = useNavigate();

  useEffect(() => {
    const checkOrg = async () => {
        console.log(user, " ", isSignedIn);
        if (isSignedIn && user) {
            try {
            const res: any = await axios.get(`/users/${user.id}`);
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
  }, [user, isSignedIn, navigate]);

  return null; // or a loading spinner if you prefer
};

export default AuthRedirect;
