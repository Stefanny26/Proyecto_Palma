--
-- PostgreSQL database cluster dump
--

SET default_transaction_read_only = off;

SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;

--
-- Roles
--

CREATE ROLE admin;
ALTER ROLE admin WITH NOSUPERUSER INHERIT NOCREATEROLE NOCREATEDB NOLOGIN NOREPLICATION NOBYPASSRLS;
CREATE ROLE admin_user;
ALTER ROLE admin_user WITH NOSUPERUSER INHERIT NOCREATEROLE NOCREATEDB LOGIN NOREPLICATION NOBYPASSRLS PASSWORD 'SCRAM-SHA-256$4096:c9Qsda/B0v0YTOM/wfOYxA==$NhhY7AvYbwKRj+/zsoxyKkOoictTTnz5mfcO6CaYJBQ=:7rpuBjjeWGyGRTzh6z/tK+WiWeH/kH9SYaARYDkc0QA=';
CREATE ROLE empleados_vendedores;
ALTER ROLE empleados_vendedores WITH NOSUPERUSER INHERIT NOCREATEROLE NOCREATEDB NOLOGIN NOREPLICATION NOBYPASSRLS;
CREATE ROLE jornalero_user1;
ALTER ROLE jornalero_user1 WITH NOSUPERUSER INHERIT NOCREATEROLE NOCREATEDB LOGIN NOREPLICATION NOBYPASSRLS PASSWORD 'SCRAM-SHA-256$4096:BvdLuEMjzC0TcWiOslpXRA==$j112/iKEKdkoVoQVxKn/sCZuz+Ils3fl8vYRgyOcOME=:TCDsdsuHXgTEBmhAOmgAraMIuIJrCxEyidrJyqnxwh8=';
CREATE ROLE jornaleros_cosechas;
ALTER ROLE jornaleros_cosechas WITH NOSUPERUSER INHERIT NOCREATEROLE NOCREATEDB NOLOGIN NOREPLICATION NOBYPASSRLS;
CREATE ROLE postgres;
ALTER ROLE postgres WITH SUPERUSER INHERIT CREATEROLE CREATEDB LOGIN REPLICATION BYPASSRLS PASSWORD 'SCRAM-SHA-256$4096:mWzbsMeT6p6Sqj04PmAtUw==$+aqaMrzXkv9KgCTGKaeYZbMkHEmWAPLLSMdlNmnxHKc=:Ug77RR62BntN+5GaTvFM9QUOAq0c2Ys2T4CyJOsXgS4=';
CREATE ROLE vendedor_user1;
ALTER ROLE vendedor_user1 WITH NOSUPERUSER INHERIT NOCREATEROLE NOCREATEDB LOGIN NOREPLICATION NOBYPASSRLS PASSWORD 'SCRAM-SHA-256$4096:EnMTOtybJ+6WwZNFxT8IcQ==$BrPBximzJ00Z8uSmnKOpfROSEKAHdaRLofgzQR/3A50=:91I6NfEXqkWdQTlKZ+BcFl6+/3Am7Jj6Uj7cYN5h9u0=';

--
-- User Configurations
--


--
-- Role memberships
--

GRANT admin TO admin_user WITH INHERIT TRUE GRANTED BY postgres;
GRANT empleados_vendedores TO vendedor_user1 WITH INHERIT TRUE GRANTED BY postgres;
GRANT jornaleros_cosechas TO jornalero_user1 WITH INHERIT TRUE GRANTED BY postgres;






--
-- Databases
--

--
-- Database "template1" dump
--

\connect template1

--
-- PostgreSQL database dump
--

-- Dumped from database version 16.3 (Debian 16.3-1.pgdg120+1)
-- Dumped by pg_dump version 16.3 (Debian 16.3-1.pgdg120+1)

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

--
-- PostgreSQL database dump complete
--

--
-- Database "Proyecto_U1_G1" dump
--

--
-- PostgreSQL database dump
--

-- Dumped from database version 16.3 (Debian 16.3-1.pgdg120+1)
-- Dumped by pg_dump version 16.3 (Debian 16.3-1.pgdg120+1)

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

--
-- Name: Proyecto_U1_G1; Type: DATABASE; Schema: -; Owner: postgres
--

CREATE DATABASE "Proyecto_U1_G1" WITH TEMPLATE = template0 ENCODING = 'UTF8' LOCALE_PROVIDER = libc LOCALE = 'en_US.utf8';


ALTER DATABASE "Proyecto_U1_G1" OWNER TO postgres;

\connect "Proyecto_U1_G1"

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

--
-- Name: actualizar_productos_por_cosecha(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.actualizar_productos_por_cosecha() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
DECLARE
    incremento INTEGER;
BEGIN
    -- Determinar el incremento en función de la cantidad cosechada
    IF NEW.cantidad_cosechada = 50 THEN
        incremento := 10;
    ELSIF NEW.cantidad_cosechada = 100 THEN
        incremento := 20;
    ELSIF NEW.cantidad_cosechada = 200 THEN
        incremento := 40;
    ELSE
        incremento := 0;
    END IF;

    -- Si el incremento es mayor a 0, actualizar la cantidad en la tabla productos para el tipo "Venta"
    IF incremento > 0 THEN
        UPDATE productos
        SET cantidad = cantidad + incremento
        WHERE id_tipo_producto = (SELECT id_tipo_producto FROM tipos_productos WHERE descripcion = 'Venta');
    END IF;

    RETURN NEW;
END;
$$;


ALTER FUNCTION public.actualizar_productos_por_cosecha() OWNER TO postgres;

--
-- Name: actualizar_productos_por_venta(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.actualizar_productos_por_venta() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
BEGIN
    -- Restar la cantidad vendida de la tabla productos
    UPDATE productos
    SET cantidad = cantidad - NEW.cantidad
    WHERE id_producto = NEW.id_producto;

    RETURN NEW;
END;
$$;


ALTER FUNCTION public.actualizar_productos_por_venta() OWNER TO postgres;

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- Name: clientes; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.clientes (
    id_cliente integer NOT NULL,
    nombre character varying(255) NOT NULL,
    contacto character varying(255) NOT NULL,
    direccion character varying(255) NOT NULL
);


ALTER TABLE public.clientes OWNER TO postgres;

--
-- Name: clientes_id_cliente_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.clientes_id_cliente_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.clientes_id_cliente_seq OWNER TO postgres;

--
-- Name: clientes_id_cliente_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.clientes_id_cliente_seq OWNED BY public.clientes.id_cliente;


--
-- Name: cosecha; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.cosecha (
    id_cosecha integer NOT NULL,
    id_parcela integer NOT NULL,
    id_empleado integer NOT NULL,
    fecha_cosecha date NOT NULL,
    cantidad_cosechada numeric(10,2) NOT NULL
);


ALTER TABLE public.cosecha OWNER TO postgres;

--
-- Name: cosecha_id_cosecha_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.cosecha_id_cosecha_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.cosecha_id_cosecha_seq OWNER TO postgres;

--
-- Name: cosecha_id_cosecha_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.cosecha_id_cosecha_seq OWNED BY public.cosecha.id_cosecha;


--
-- Name: detalles_ventas; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.detalles_ventas (
    id_detalle_venta integer NOT NULL,
    id_venta integer,
    id_producto integer,
    cantidad integer NOT NULL,
    precio_unitario numeric(10,2) NOT NULL
);


ALTER TABLE public.detalles_ventas OWNER TO postgres;

--
-- Name: detalles_ventas_id_detalle_venta_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.detalles_ventas_id_detalle_venta_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.detalles_ventas_id_detalle_venta_seq OWNER TO postgres;

--
-- Name: detalles_ventas_id_detalle_venta_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.detalles_ventas_id_detalle_venta_seq OWNED BY public.detalles_ventas.id_detalle_venta;


--
-- Name: empleados; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.empleados (
    id_empleado integer NOT NULL,
    nombre character varying(255) NOT NULL,
    apellido character varying(255) NOT NULL,
    id_tipo_empleado integer NOT NULL,
    fecha_contratacion date NOT NULL,
    salario numeric(10,2) NOT NULL
);


ALTER TABLE public.empleados OWNER TO postgres;

--
-- Name: empleados_id_empleado_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.empleados_id_empleado_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.empleados_id_empleado_seq OWNER TO postgres;

--
-- Name: empleados_id_empleado_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.empleados_id_empleado_seq OWNED BY public.empleados.id_empleado;


--
-- Name: tipos_empleados; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.tipos_empleados (
    id_tipo_empleado integer NOT NULL,
    descripcion character varying(255) NOT NULL
);


ALTER TABLE public.tipos_empleados OWNER TO postgres;

--
-- Name: ventas; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ventas (
    id_venta integer NOT NULL,
    id_cliente integer,
    id_empleado integer,
    fecha_venta timestamp without time zone NOT NULL
);


ALTER TABLE public.ventas OWNER TO postgres;

--
-- Name: empleadosconmasventas; Type: VIEW; Schema: public; Owner: postgres
--

CREATE VIEW public.empleadosconmasventas AS
 SELECT e.nombre,
    count(v.id_empleado) AS totalventas
   FROM ((public.empleados e
     JOIN public.tipos_empleados t ON ((t.id_tipo_empleado = e.id_tipo_empleado)))
     JOIN public.ventas v ON ((e.id_empleado = v.id_empleado)))
  WHERE ((t.descripcion)::text = 'Vendedor'::text)
  GROUP BY e.nombre
  ORDER BY (count(v.id_empleado)) DESC;


ALTER VIEW public.empleadosconmasventas OWNER TO postgres;

--
-- Name: login; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.login (
    id integer NOT NULL,
    username character varying(50) NOT NULL,
    password character varying(255) NOT NULL,
    role character varying(50) NOT NULL
);


ALTER TABLE public.login OWNER TO postgres;

--
-- Name: login_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.login_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.login_id_seq OWNER TO postgres;

--
-- Name: login_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.login_id_seq OWNED BY public.login.id;


--
-- Name: parcelas; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.parcelas (
    id_parcela integer NOT NULL,
    cantidad_de_plantas integer NOT NULL,
    tipo_suelo character varying(255) NOT NULL,
    fecha_plantacion date NOT NULL
);


ALTER TABLE public.parcelas OWNER TO postgres;

--
-- Name: parcelas_id_parcela_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.parcelas_id_parcela_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.parcelas_id_parcela_seq OWNER TO postgres;

--
-- Name: parcelas_id_parcela_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.parcelas_id_parcela_seq OWNED BY public.parcelas.id_parcela;


--
-- Name: productos; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.productos (
    id_producto integer NOT NULL,
    nombre character varying(255) NOT NULL,
    id_tipo_producto integer NOT NULL,
    precio numeric(10,2) NOT NULL,
    cantidad integer NOT NULL
);


ALTER TABLE public.productos OWNER TO postgres;

--
-- Name: productos_id_producto_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.productos_id_producto_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.productos_id_producto_seq OWNER TO postgres;

--
-- Name: productos_id_producto_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.productos_id_producto_seq OWNED BY public.productos.id_producto;


--
-- Name: tipos_empleados_id_tipo_empleado_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.tipos_empleados_id_tipo_empleado_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.tipos_empleados_id_tipo_empleado_seq OWNER TO postgres;

--
-- Name: tipos_empleados_id_tipo_empleado_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.tipos_empleados_id_tipo_empleado_seq OWNED BY public.tipos_empleados.id_tipo_empleado;


--
-- Name: tipos_productos; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.tipos_productos (
    id_tipo_producto integer NOT NULL,
    descripcion character varying(255) NOT NULL
);


ALTER TABLE public.tipos_productos OWNER TO postgres;

--
-- Name: tipos_productos_id_tipo_producto_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.tipos_productos_id_tipo_producto_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.tipos_productos_id_tipo_producto_seq OWNER TO postgres;

--
-- Name: tipos_productos_id_tipo_producto_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.tipos_productos_id_tipo_producto_seq OWNED BY public.tipos_productos.id_tipo_producto;


--
-- Name: ventas_id_venta_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.ventas_id_venta_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ventas_id_venta_seq OWNER TO postgres;

--
-- Name: ventas_id_venta_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.ventas_id_venta_seq OWNED BY public.ventas.id_venta;


--
-- Name: ventasconproductos; Type: VIEW; Schema: public; Owner: postgres
--

CREATE VIEW public.ventasconproductos AS
 SELECT v.id_venta,
    c.nombre,
    e.nombre AS n_empleado,
    string_agg((p.nombre)::text, ', '::text) AS productos,
    sum(dv.cantidad) AS total_cantidad,
    sum(((dv.cantidad)::numeric * dv.precio_unitario)) AS total_precio
   FROM ((((public.ventas v
     JOIN public.empleados e ON ((v.id_empleado = e.id_empleado)))
     JOIN public.clientes c ON ((c.id_cliente = v.id_cliente)))
     JOIN public.detalles_ventas dv ON ((v.id_venta = dv.id_venta)))
     JOIN public.productos p ON ((dv.id_producto = p.id_producto)))
  GROUP BY v.id_venta, c.nombre, e.nombre
  ORDER BY v.id_venta;


ALTER VIEW public.ventasconproductos OWNER TO postgres;

--
-- Name: clientes id_cliente; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.clientes ALTER COLUMN id_cliente SET DEFAULT nextval('public.clientes_id_cliente_seq'::regclass);


--
-- Name: cosecha id_cosecha; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.cosecha ALTER COLUMN id_cosecha SET DEFAULT nextval('public.cosecha_id_cosecha_seq'::regclass);


--
-- Name: detalles_ventas id_detalle_venta; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.detalles_ventas ALTER COLUMN id_detalle_venta SET DEFAULT nextval('public.detalles_ventas_id_detalle_venta_seq'::regclass);


--
-- Name: empleados id_empleado; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.empleados ALTER COLUMN id_empleado SET DEFAULT nextval('public.empleados_id_empleado_seq'::regclass);


--
-- Name: login id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.login ALTER COLUMN id SET DEFAULT nextval('public.login_id_seq'::regclass);


--
-- Name: parcelas id_parcela; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.parcelas ALTER COLUMN id_parcela SET DEFAULT nextval('public.parcelas_id_parcela_seq'::regclass);


--
-- Name: productos id_producto; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.productos ALTER COLUMN id_producto SET DEFAULT nextval('public.productos_id_producto_seq'::regclass);


--
-- Name: tipos_empleados id_tipo_empleado; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.tipos_empleados ALTER COLUMN id_tipo_empleado SET DEFAULT nextval('public.tipos_empleados_id_tipo_empleado_seq'::regclass);


--
-- Name: tipos_productos id_tipo_producto; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.tipos_productos ALTER COLUMN id_tipo_producto SET DEFAULT nextval('public.tipos_productos_id_tipo_producto_seq'::regclass);


--
-- Name: ventas id_venta; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ventas ALTER COLUMN id_venta SET DEFAULT nextval('public.ventas_id_venta_seq'::regclass);


--
-- Data for Name: clientes; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.clientes (id_cliente, nombre, contacto, direccion) FROM stdin;
1	Santiago Martínez	santiago.martinez@example.com	Calle Principal 123
2	Lucía Rodríguez	lucia.rodriguez@example.com	Avenida Central 456
3	Mateo García	mateo.garcia@example.com	Plaza Mayor 789
4	Valentina López	valentina.lopez@example.com	Paseo del Bosque 101
5	Emilio Sánchez	emilio.sanchez@example.com	Calle del Sol 202
6	Catalina Pérez	catalina.perez@example.com	Avenida Primavera 303
7	Dylan Martín	dylan.martin@example.com	Plaza del Ayuntamiento 404
8	Martina Torres	martina.torres@example.com	Calle Mayor 505
9	Nicolás Ruiz	nicolas.ruiz@example.com	Avenida de la Paz 606
10	Isabella Flores	isabella.flores@example.com	Plaza de la Constitución 707
11	Sebastián González	sebastian.gonzalez@example.com	Calle Real 808
12	Valeria Ramírez	valeria.ramirez@example.com	Avenida Libertad 909
13	Benjamín Herrera	benjamin.herrera@example.com	Plaza de España 010
14	Constanza Díaz	constanza.diaz@example.com	Calle de las Flores 111
15	Matías Vargas	matias.vargas@example.com	Avenida de la Victoria 212
16	Agustina Castro	agustina.castro@example.com	Plaza de la Paz 313
17	Gaspar Martínez	gaspar.martinez@example.com	Calle Nueva 414
18	Renata Jiménez	renata.jimenez@example.com	Avenida de la Libertad 515
19	Bautista Silva	bautista.silva@example.com	Plaza del Carmen 616
20	Julieta Morales	julieta.morales@example.com	Calle del Río 717
\.


--
-- Data for Name: cosecha; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.cosecha (id_cosecha, id_parcela, id_empleado, fecha_cosecha, cantidad_cosechada) FROM stdin;
1	1	2	2023-03-10	500.00
2	2	2	2023-04-15	650.00
3	3	2	2023-05-20	700.00
\.


--
-- Data for Name: detalles_ventas; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.detalles_ventas (id_detalle_venta, id_venta, id_producto, cantidad, precio_unitario) FROM stdin;
1	1	1	20	3.50
2	2	2	10	4.00
3	3	1	30	3.50
\.


--
-- Data for Name: empleados; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.empleados (id_empleado, nombre, apellido, id_tipo_empleado, fecha_contratacion, salario) FROM stdin;
1	María	Martínez	2	2021-01-05	2000.00
2	Pedro	González	2	2021-02-12	1800.00
3	Luisa	Hernández	1	2019-11-20	2800.00
4	Jorge	Díaz	3	2020-08-15	2500.00
5	Gabriela	Rodríguez	2	2022-03-25	2200.00
6	Diego	López	1	2020-07-30	2600.00
7	Laura	Sánchez	2	2021-05-10	1900.00
8	Roberto	Pérez	3	2019-09-18	2700.00
9	Mónica	García	2	2020-04-22	2100.00
10	Fernando	Martín	1	2021-08-08	2400.00
11	Ana	Ramírez	3	2022-01-14	2300.00
12	Carlos	Torres	2	2019-12-10	2000.00
13	Elena	Flores	1	2020-06-05	2200.00
14	David	Ruiz	2	2021-10-30	2300.00
15	Sandra	Vargas	3	2020-02-15	2500.00
\.


--
-- Data for Name: login; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.login (id, username, password, role) FROM stdin;
1	admin_user	admin	Admin
2	vendedor_user1	vendedor1	Vendedor
3	jornalero_user1	jornalero1	Palmicultor
\.


--
-- Data for Name: parcelas; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.parcelas (id_parcela, cantidad_de_plantas, tipo_suelo, fecha_plantacion) FROM stdin;
1	150	Arcilloso	2022-02-10
2	200	Arenoso	2021-06-15
3	180	Limoso	2020-09-20
\.


--
-- Data for Name: productos; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.productos (id_producto, nombre, id_tipo_producto, precio, cantidad) FROM stdin;
3	Champú de Palma	2	4.50	100
4	Cera de Palma	2	6.00	60
5	Gel de Ducha de Palma	2	4.00	150
6	Abono	1	15.00	26
7	Fertilizante	1	23.00	55
2	Jabón de Palma	2	3.00	110
1	Crema de Palma	2	5.50	30
\.


--
-- Data for Name: tipos_empleados; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.tipos_empleados (id_tipo_empleado, descripcion) FROM stdin;
1	Supervisor
2	Palmicultores
3	Vendedor
\.


--
-- Data for Name: tipos_productos; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.tipos_productos (id_tipo_producto, descripcion) FROM stdin;
1	Almacenamiento
2	Venta
\.


--
-- Data for Name: ventas; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.ventas (id_venta, id_cliente, id_empleado, fecha_venta) FROM stdin;
1	1	4	2023-06-10 10:30:00
2	2	11	2023-06-12 14:45:00
3	3	4	2023-06-10 10:30:00
\.


--
-- Name: clientes_id_cliente_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.clientes_id_cliente_seq', 20, true);


--
-- Name: cosecha_id_cosecha_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.cosecha_id_cosecha_seq', 3, true);


--
-- Name: detalles_ventas_id_detalle_venta_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.detalles_ventas_id_detalle_venta_seq', 3, true);


--
-- Name: empleados_id_empleado_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.empleados_id_empleado_seq', 15, true);


--
-- Name: login_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.login_id_seq', 3, true);


--
-- Name: parcelas_id_parcela_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.parcelas_id_parcela_seq', 3, true);


--
-- Name: productos_id_producto_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.productos_id_producto_seq', 7, true);


--
-- Name: tipos_empleados_id_tipo_empleado_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.tipos_empleados_id_tipo_empleado_seq', 3, true);


--
-- Name: tipos_productos_id_tipo_producto_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.tipos_productos_id_tipo_producto_seq', 2, true);


--
-- Name: ventas_id_venta_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.ventas_id_venta_seq', 3, true);


--
-- Name: clientes clientes_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.clientes
    ADD CONSTRAINT clientes_pkey PRIMARY KEY (id_cliente);


--
-- Name: cosecha cosecha_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.cosecha
    ADD CONSTRAINT cosecha_pkey PRIMARY KEY (id_cosecha);


--
-- Name: detalles_ventas detalles_ventas_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.detalles_ventas
    ADD CONSTRAINT detalles_ventas_pkey PRIMARY KEY (id_detalle_venta);


--
-- Name: empleados empleados_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.empleados
    ADD CONSTRAINT empleados_pkey PRIMARY KEY (id_empleado);


--
-- Name: login login_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.login
    ADD CONSTRAINT login_pkey PRIMARY KEY (id);


--
-- Name: login login_username_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.login
    ADD CONSTRAINT login_username_key UNIQUE (username);


--
-- Name: parcelas parcelas_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.parcelas
    ADD CONSTRAINT parcelas_pkey PRIMARY KEY (id_parcela);


--
-- Name: productos productos_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.productos
    ADD CONSTRAINT productos_pkey PRIMARY KEY (id_producto);


--
-- Name: tipos_empleados tipos_empleados_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.tipos_empleados
    ADD CONSTRAINT tipos_empleados_pkey PRIMARY KEY (id_tipo_empleado);


--
-- Name: tipos_productos tipos_productos_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.tipos_productos
    ADD CONSTRAINT tipos_productos_pkey PRIMARY KEY (id_tipo_producto);


--
-- Name: ventas ventas_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ventas
    ADD CONSTRAINT ventas_pkey PRIMARY KEY (id_venta);


--
-- Name: cosecha trigger_actualizar_productos_por_cosecha; Type: TRIGGER; Schema: public; Owner: postgres
--

CREATE TRIGGER trigger_actualizar_productos_por_cosecha AFTER INSERT ON public.cosecha FOR EACH ROW EXECUTE FUNCTION public.actualizar_productos_por_cosecha();


--
-- Name: detalles_ventas trigger_actualizar_productos_por_venta; Type: TRIGGER; Schema: public; Owner: postgres
--

CREATE TRIGGER trigger_actualizar_productos_por_venta AFTER INSERT ON public.detalles_ventas FOR EACH ROW EXECUTE FUNCTION public.actualizar_productos_por_venta();


--
-- Name: cosecha cosecha_id_empleado_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.cosecha
    ADD CONSTRAINT cosecha_id_empleado_fkey FOREIGN KEY (id_empleado) REFERENCES public.empleados(id_empleado);


--
-- Name: cosecha cosecha_id_parcela_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.cosecha
    ADD CONSTRAINT cosecha_id_parcela_fkey FOREIGN KEY (id_parcela) REFERENCES public.parcelas(id_parcela);


--
-- Name: detalles_ventas detalles_ventas_id_producto_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.detalles_ventas
    ADD CONSTRAINT detalles_ventas_id_producto_fkey FOREIGN KEY (id_producto) REFERENCES public.productos(id_producto);


--
-- Name: detalles_ventas detalles_ventas_id_venta_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.detalles_ventas
    ADD CONSTRAINT detalles_ventas_id_venta_fkey FOREIGN KEY (id_venta) REFERENCES public.ventas(id_venta);


--
-- Name: empleados empleados_id_tipo_empleado_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.empleados
    ADD CONSTRAINT empleados_id_tipo_empleado_fkey FOREIGN KEY (id_tipo_empleado) REFERENCES public.tipos_empleados(id_tipo_empleado);


--
-- Name: productos productos_id_tipo_producto_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.productos
    ADD CONSTRAINT productos_id_tipo_producto_fkey FOREIGN KEY (id_tipo_producto) REFERENCES public.tipos_productos(id_tipo_producto);


--
-- Name: ventas ventas_id_cliente_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ventas
    ADD CONSTRAINT ventas_id_cliente_fkey FOREIGN KEY (id_cliente) REFERENCES public.clientes(id_cliente);


--
-- Name: ventas ventas_id_empleado_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ventas
    ADD CONSTRAINT ventas_id_empleado_fkey FOREIGN KEY (id_empleado) REFERENCES public.empleados(id_empleado);


--
-- Name: DATABASE "Proyecto_U1_G1"; Type: ACL; Schema: -; Owner: postgres
--

GRANT ALL ON DATABASE "Proyecto_U1_G1" TO admin;
GRANT CONNECT ON DATABASE "Proyecto_U1_G1" TO empleados_vendedores;
GRANT CONNECT ON DATABASE "Proyecto_U1_G1" TO jornaleros_cosechas;


--
-- Name: SCHEMA public; Type: ACL; Schema: -; Owner: pg_database_owner
--

GRANT USAGE ON SCHEMA public TO empleados_vendedores;
GRANT USAGE ON SCHEMA public TO jornaleros_cosechas;


--
-- Name: TABLE clientes; Type: ACL; Schema: public; Owner: postgres
--

GRANT SELECT ON TABLE public.clientes TO empleados_vendedores;


--
-- Name: TABLE cosecha; Type: ACL; Schema: public; Owner: postgres
--

GRANT SELECT,INSERT,UPDATE ON TABLE public.cosecha TO jornaleros_cosechas;


--
-- Name: TABLE detalles_ventas; Type: ACL; Schema: public; Owner: postgres
--

GRANT SELECT,INSERT,DELETE,UPDATE ON TABLE public.detalles_ventas TO empleados_vendedores;


--
-- Name: TABLE empleados; Type: ACL; Schema: public; Owner: postgres
--

GRANT SELECT ON TABLE public.empleados TO empleados_vendedores;
GRANT SELECT ON TABLE public.empleados TO jornaleros_cosechas;


--
-- Name: TABLE ventas; Type: ACL; Schema: public; Owner: postgres
--

GRANT SELECT,INSERT,DELETE,UPDATE ON TABLE public.ventas TO empleados_vendedores;


--
-- Name: TABLE empleadosconmasventas; Type: ACL; Schema: public; Owner: postgres
--

GRANT SELECT ON TABLE public.empleadosconmasventas TO empleados_vendedores;


--
-- Name: TABLE parcelas; Type: ACL; Schema: public; Owner: postgres
--

GRANT SELECT,INSERT,UPDATE ON TABLE public.parcelas TO jornaleros_cosechas;


--
-- Name: TABLE productos; Type: ACL; Schema: public; Owner: postgres
--

GRANT SELECT ON TABLE public.productos TO empleados_vendedores;
GRANT SELECT ON TABLE public.productos TO jornaleros_cosechas;


--
-- Name: TABLE ventasconproductos; Type: ACL; Schema: public; Owner: postgres
--

GRANT SELECT ON TABLE public.ventasconproductos TO empleados_vendedores;


--
-- PostgreSQL database dump complete
--

--
-- Database "postgres" dump
--

\connect postgres

--
-- PostgreSQL database dump
--

-- Dumped from database version 16.3 (Debian 16.3-1.pgdg120+1)
-- Dumped by pg_dump version 16.3 (Debian 16.3-1.pgdg120+1)

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

--
-- PostgreSQL database dump complete
--

--
-- PostgreSQL database cluster dump complete
--

