import { Container, Nav, Navbar } from 'react-bootstrap';
import { useNavigate } from 'react-router-dom';
import { useAuth } from '../context/AuthContext';

export default function Layout({ children }) {
  const { user, logout } = useAuth();
  const navigate = useNavigate();

  async function handleLogout() {
    await logout();
    navigate('/login');
  }

  return (
    <>
      <Navbar bg="dark" variant="dark" className="mb-4">
        <Container>
          <Navbar.Brand>Slither Admin</Navbar.Brand>
          <Nav className="ms-auto d-flex align-items-center gap-3">
            <span className="text-light">{user?.name}</span>
            <Nav.Link onClick={handleLogout} className="text-light">
              Cerrar sesión
            </Nav.Link>
          </Nav>
        </Container>
      </Navbar>
      <Container>{children}</Container>
    </>
  );
}
