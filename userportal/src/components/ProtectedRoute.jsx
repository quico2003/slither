import { Navigate } from 'react-router-dom';
import { useAuth } from '../context/AuthContext';

export default function ProtectedRoute({ children }) {
  const { user } = useAuth();

  if (!user || user.role !== 'user') {
    return <Navigate to="/login" replace />;
  }

  return children;
}
