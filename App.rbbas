#tag Class
Protected Class App
Inherits ConsoleApplication
	#tag Event
		Function Run(args() as String) As Integer
		  
		  #pragma Unused args
		  
		  Me.Tracker = New PvPGNTracker()
		  Me.TrackAPI = New PvPGNTrackAPI()
		  
		  Me.Tracker.Connect()
		  
		  Do
		    Me.DoEvents( 1 )
		    Me.YieldToNextThread()
		  Loop
		  
		  Return 0
		  
		End Function
	#tag EndEvent


	#tag Method, Flags = &h0
		Function TimeString(Seconds As UInt64) As String
		  
		  Dim buf As String
		  Dim weeks, days, hours, minutes As UInt64
		  
		  minutes = seconds \ 60
		  seconds = seconds Mod 60
		  
		  hours = minutes \ 60
		  minutes = minutes Mod 60
		  
		  days = hours \ 24
		  hours = hours Mod 24
		  
		  weeks = days \ 7
		  days = days Mod 7
		  
		  If seconds > 0 Then
		    buf = buf + " "
		    If seconds <> 1 Then buf = "s" + buf
		    buf = "second" + buf
		    buf = Format( seconds, "-#" ) + buf
		  End If
		  
		  If minutes > 0 Then
		    buf = buf + " "
		    If minutes <> 1 Then buf = "s" + buf
		    buf = "minute" + buf
		    buf = Format( minutes, "-#" ) + buf
		  End If
		  
		  If hours > 0 Then
		    buf = buf + " "
		    If hours <> 1 Then buf = "s" + buf
		    buf = "hour" + buf
		    buf = Format( hours, "-#" ) + buf
		  End If
		  
		  If days > 0 Then
		    buf = buf + " "
		    If days <> 1 Then buf = "s" + buf
		    buf = "day" + buf
		    buf = Format( days, "-#" ) + buf
		  End If
		  
		  If weeks > 0 Then
		    buf = buf + " "
		    If weeks <> 1 Then buf = "s" + buf
		    buf = "week" + buf
		    buf = Format( weeks, "-#" ) + buf
		  End If
		  
		  Return Left( buf, Len( buf ) - 1 )
		  
		End Function
	#tag EndMethod

	#tag Method, Flags = &h0
		Function ToHexString(value As String, delimiter As String = "") As String
		  
		  Dim buf As String
		  Dim i As Integer
		  Dim c As Integer
		  
		  c = LenB( value )
		  
		  For i = 1 To c
		    buf = buf + Right( "0" + Hex( AscB( MidB( value, i, 1 ))), 2 ) + delimiter
		  Next
		  
		  If Len( delimiter ) > 0 Then
		    buf = Left( buf, Len( buf ) - Len( delimiter ))
		  End If
		  
		  Return buf
		  
		End Function
	#tag EndMethod


	#tag Property, Flags = &h0
		TrackAPI As PvPGNTrackAPI
	#tag EndProperty

	#tag Property, Flags = &h0
		Tracker As PvPGNTracker
	#tag EndProperty


	#tag ViewBehavior
	#tag EndViewBehavior
End Class
#tag EndClass
