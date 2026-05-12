# 🤝 Contributing Guidelines

<div align="center">

### 🚀 Guía de contribución para Nyetflix

Gracias por tu interés en contribuir al proyecto ❤️

</div>

---

# 🌟 Cómo contribuir

Si deseas colaborar con código en este proyecto, sigue los siguientes pasos:

## 1️⃣ Fork del repositorio

Haz un fork del proyecto hacia tu cuenta de GitHub.

---

## 2️⃣ Realiza cambios en tu fork

Trabaja en tus mejoras, correcciones o nuevas funcionalidades dentro de tu propia copia del repositorio.

---

## 3️⃣ Verifica y prueba tu código

Antes de enviar cambios:

- ✅ Verifica que todo funcione correctamente
- ✅ Asegúrate de no romper funcionalidades existentes
- ✅ Comprueba integración con el código actual
- ✅ Mantén consistencia con el proyecto

---

## 4️⃣ Crear Pull Request

Cuando tu contribución esté lista:

```bash
git checkout -b feature/new-feature
```

```bash
git commit -m "✨ Nueva funcionalidad"
```

```bash
git push origin feature/new-feature
```

Luego crea un **Pull Request** 🚀

---

# 🗄️ Configuración de base de datos

## 📦 Importar base de datos

El proyecto incluye un dump SQL disponible en:

```bash
/backend/database.sql
```

Importa este archivo en tu servidor MySQL o compatible.

---

# ⚙️ Configuración recomendada

Para que el proyecto funcione sin modificaciones adicionales, asegúrate de cumplir con lo siguiente:

| Configuración | Valor |
|---|---|
| Database Name | `nyetflix` |
| Database User | `nyetflix` |
| Password | `nyetflix` |
| Puerto MySQL | Default |
| Network Access | Enabled |

---

# 🔐 Permisos requeridos

El usuario de la base de datos debe tener al menos:

```sql
USAGE
```

sobre la base de datos:

```sql
nyetflix
```

---

# 🛠️ Configuración personalizada

Si deseas utilizar otra configuración:

- Nombre diferente de base de datos
- Usuario distinto
- Contraseña personalizada
- Puerto personalizado

Puedes modificar la configuración de conexión dentro de:

```bash
backend.php
```

---

# 🌐 Documentación API

La documentación del backend y endpoints API puede encontrarse en:

```bash
API.md
```

---

# 📋 Recomendaciones

## ✅ Buenas prácticas

- Mantén el código limpio
- Usa nombres descriptivos
- Evita código innecesario
- Documenta nuevas funcionalidades
- Respeta la estructura del proyecto

---

# 🐛 Reportar problemas

Si encuentras errores:

1. Abrir un issue
2. Explicar el problema claramente
3. Agregar pasos para reproducirlo
4. Incluir logs o capturas si es necesario

---

# 💡 Ideas y mejoras

Las sugerencias son bienvenidas ❤️

Puedes contribuir con:

- 🎨 Mejoras UI/UX
- ⚡ Optimización de rendimiento
- 🔐 Seguridad
- 📱 Responsive Design
- 🎬 Funcionalidades multimedia
- 🗄️ Backend APIs

---

<div align="center">

### 🚀 Gracias por contribuir a Nyetflix

Tu ayuda hace crecer el proyecto ❤️

</div>
