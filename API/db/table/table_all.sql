
/****** Object:  Table [dbo].[personal_access_tokens]    Script Date: 2024/04/19 13:47:54 ******/
IF  EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[personal_access_tokens]') AND type in (N'U'))
DROP TABLE [dbo].[personal_access_tokens]
GO

/****** Object:  Table [dbo].[personal_access_tokens]    Script Date: 2024/04/19 13:47:54 ******/
SET ANSI_NULLS ON
GO

SET QUOTED_IDENTIFIER ON
GO

CREATE TABLE [dbo].[personal_access_tokens](
	[id] [bigint] IDENTITY(1,1) NOT NULL,
	[tokenable_type] [nvarchar](255) NOT NULL,
	[tokenable_id] [bigint] NOT NULL,
	[name] [nvarchar](255) NOT NULL,
	[token] [nvarchar](64) NOT NULL,
	[abilities] [nvarchar](max) NULL,
	[last_used_at] [datetime] NULL,
	[expires_at] [datetime] NULL,
	[created_at] [datetime] NULL,
	[updated_at] [datetime] NULL,
PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, OPTIMIZE_FOR_SEQUENTIAL_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY] TEXTIMAGE_ON [PRIMARY]
GO


/****** Object:  Table [dbo].[account]    Script Date: 2024/04/19 13:49:11 ******/
IF  EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[account]') AND type in (N'U'))
DROP TABLE [dbo].[account]
GO

/****** Object:  Table [dbo].[account]    Script Date: 2024/04/19 13:49:11 ******/
SET ANSI_NULLS ON
GO

SET QUOTED_IDENTIFIER ON
GO

CREATE TABLE [dbo].[account](
	[id] [bigint] IDENTITY(1,1) NOT NULL,
	[user_nm] [nvarchar](50) NULL,
	[address] [nvarchar](50) NULL,
	[tel] [int] NULL,
	[email] [nvarchar](50) NULL,
	[password] [nvarchar](max) NULL,
	[role] [int] NULL,
	[cre_date] [datetime2](7) NULL,
	[upd_date] [datetime2](7) NULL,
	[del_date] [datetime2](7) NULL,
	[del_flg] [int] NULL
) ON [PRIMARY] TEXTIMAGE_ON [PRIMARY]
GO


