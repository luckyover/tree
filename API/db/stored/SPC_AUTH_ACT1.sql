DROP PROCEDURE [SPC_AUTH_ACT1]
GO
/****** Object:  StoredProcedure [dbo].[SPC_WAREHOUSE_ACT1]    Script Date: 2024/04/19 16:13:19 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

--*
--****************************************************************************************
CREATE PROCEDURE [dbo].[SPC_AUTH_ACT1] 
	-- Add the parameters for the stored procedure here
	@P_json						nvarchar(max)			=	N''
AS
BEGIN
	SET NOCOUNT ON;
	DECLARE 
		@w_time					DATETIME2			=	SYSDATETIME()
    --■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■
    --PREPARE PROCESS
    --■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■
    CREATE TABLE #TABLE_JSON (
        id                                      BIGINT      IDENTITY(1,1)
    ,   email									NVARCHAR(200)
    )
	INSERT INTO #TABLE_JSON
	SELECT
		ISNULL(json_temp.email,'')
	FROM OPENJSON(@P_json,'$.list') WITH(
	    email                              NVARCHAR(200)
	)AS json_temp

    --■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■
    --PROCESS
    --■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■
	-- START TRANSACTION
    BEGIN TRY
	BEGIN TRANSACTION
       UPDATE account
       SET
            del_flg =  1
       ,    del_date = @w_time
	   FROM account
       LEFT JOIN #TABLE_JSON ON (
           account.email = #TABLE_JSON.email
       )
       WHERE 
            #TABLE_JSON.email IS NULL
       AND  account.role = 0
      
	END TRY
	BEGIN CATCH
	IF (@@TRANCOUNT > 0)
		BEGIN
			ROLLBACK TRANSACTION
		END
		
	END CATCH
	--DELETE FROM @ERR_TBL
	IF(@@TRANCOUNT > 0)
	BEGIN
		COMMIT TRANSACTION
	END
    -- Insert statements for procedure here
	COMPLETE_QUERY:
    select 1
	    
END
